<?php

namespace App\Facturacion;

use App\Facturacion\Dto\ComprobanteDto;
use App\Facturacion\Dto\NotaCreditoDto;
use App\Facturacion\Dto\ResultadoFacturacion;
use App\Models\ConfiguracionFacturacion;
use App\Models\FacturaElectronica;
use App\Models\Tienda;
use App\Models\Venta;
use Illuminate\Support\Facades\Storage;

/**
 * Fachada del módulo de facturación para el ERP. Construye los datos desde una
 * Venta, resuelve la configuración de SUNAT de la tienda (o demo), delega en el
 * adaptador del país y persiste el resultado.
 */
class ServicioFacturacion
{
    public function __construct(private FabricaProveedor $fabrica) {}

    // ===================== EMISIÓN =====================

    public function emitirDesdeVenta(Venta $venta): FacturaElectronica
    {
        $venta->loadMissing('detalles', 'cliente', 'tienda');
        $cfg = $this->resolverSunat($venta->tienda);
        $this->verificarActiva($cfg);

        $registro = $this->comprobantePrincipal($venta);
        if ($registro && $registro->estaAceptado()) {
            return $registro;
        }

        $dto = $this->dtoDesdeVenta($venta, $cfg);
        $resultado = $this->fabrica->para($dto->pais, $cfg['sunat'])->emitir($dto);

        return $this->guardarComprobante($venta, $dto, $resultado, $registro);
    }

    // ===================== NOTA DE CRÉDITO =====================

    public function emitirNotaCreditoDesdeVenta(Venta $venta, string $desMotivo = 'ANULACION DE LA OPERACION', string $codMotivo = '01'): FacturaElectronica
    {
        $venta->loadMissing('detalles', 'cliente', 'tienda');
        $cfg = $this->resolverSunat($venta->tienda);
        $this->verificarActiva($cfg);

        $principal = $this->comprobantePrincipal($venta);
        if (!$principal || !$principal->estaAceptado()) {
            throw new \RuntimeException('Solo se puede emitir una nota de crédito sobre un comprobante aceptado.');
        }

        $base = $this->dtoDesdeVenta($venta, $cfg);
        $tipoAfectado = $principal->esFactura() ? '01' : '03';
        $serieNc = $principal->esFactura() ? 'FC01' : 'BC01';
        $dtoNc = $this->clonarDtoComo($base, 'NOTA_CREDITO', $serieNc, $this->siguienteCorrelativo('NOTA_CREDITO', $serieNc));

        $ncDto = new NotaCreditoDto(
            comprobante: $dtoNc,
            tipoDocAfectado: $tipoAfectado,
            numDocAfectado: $principal->serie . '-' . $principal->correlativo,
            codMotivo: $codMotivo,
            desMotivo: $desMotivo,
        );

        $resultado = $this->fabrica->para($dtoNc->pais, $cfg['sunat'])->emitirNotaCredito($ncDto);

        return $this->guardarComprobante($venta, $dtoNc, $resultado, null, [
            'documento_afectado_id' => $principal->id,
            'motivo' => $desMotivo,
        ]);
    }

    // ===================== COMUNICACIÓN DE BAJA =====================

    public function anularDesdeVenta(Venta $venta, string $motivo = 'ERROR EN LA OPERACION'): FacturaElectronica
    {
        $venta->loadMissing('tienda');
        $cfg = $this->resolverSunat($venta->tienda);

        $principal = $this->comprobantePrincipal($venta);
        if (!$principal || !$principal->estaAceptado()) {
            throw new \RuntimeException('No hay un comprobante aceptado para dar de baja.');
        }
        if (!$principal->esFactura()) {
            throw new \RuntimeException('La comunicación de baja aplica solo a facturas. Para boletas usa una nota de crédito.');
        }

        $resultado = $this->fabrica->para($principal->pais, $cfg['sunat'])
            ->anular($principal->serie, (int) $principal->correlativo, '01', $motivo);

        $principal->fill([
            'estado'  => $resultado->estado,
            'codigo'  => $resultado->codigo,
            'mensaje' => $resultado->mensaje,
            'motivo'  => $motivo,
            'ticket'  => $resultado->extra['ticket'] ?? null,
        ]);
        $principal->save();

        return $principal;
    }

    public function consultarTicket(FacturaElectronica $comprobante): FacturaElectronica
    {
        if (empty($comprobante->ticket)) {
            throw new \RuntimeException('Este comprobante no tiene un ticket pendiente.');
        }

        $venta = $comprobante->venta()->with('tienda')->first();
        $cfg = $this->resolverSunat($venta?->tienda);

        $resultado = $this->fabrica->para($comprobante->pais, $cfg['sunat'])->consultarEstado($comprobante->ticket);

        $estado = $resultado->fueAceptado() ? 'ANULADO' : $resultado->estado;
        $comprobante->fill([
            'estado'  => $estado,
            'codigo'  => $resultado->codigo,
            'mensaje' => $resultado->mensaje ?: $comprobante->mensaje,
        ]);
        if ($resultado->cdr) {
            $comprobante->cdr_path = $this->guardarArchivo($comprobante, 'cdr-baja.zip', base64_decode($resultado->cdr));
        }
        $comprobante->save();

        return $comprobante;
    }

    // ===================== CONFIGURACIÓN =====================

    /**
     * Resuelve la configuración efectiva de SUNAT para una tienda.
     * Si no hay configuración o está en modo demo, usa las credenciales y el
     * certificado de prueba globales (.env / config).
     */
    private function resolverSunat(?Tienda $tienda): array
    {
        $global = config('facturacion.sunat');
        $row = $tienda ? ConfiguracionFacturacion::where('tienda_id', $tienda->id)->first() : null;

        if (!$row || $row->modo === 'beta_demo') {
            return [
                'activo' => $row ? (bool) $row->activo : true,
                'demo'   => true,
                'sunat'  => [
                    'modo'        => 'beta_demo',
                    'ruc'         => $global['ruc'],
                    'sol_usuario' => $global['sol_usuario'],
                    'sol_clave'   => $global['sol_clave'],
                    'certificado' => $global['certificado'],
                ],
                'emisor_ruc'   => $global['ruc'],
                'emisor_razon' => 'EMPRESA DEMO SAC',
            ];
        }

        return [
            'activo' => (bool) $row->activo,
            'demo'   => false,
            'sunat'  => [
                'modo'        => $row->modo,
                'ruc'         => $row->ruc,
                'sol_usuario' => $row->sol_usuario,
                'sol_clave'   => $row->sol_clave,               // descifrada por el cast del modelo
                'certificado' => storage_path('app/' . $row->certificado_path),
            ],
            'emisor_ruc'   => $row->ruc,
            'emisor_razon' => $tienda->nombre ?? 'EMPRESA',
        ];
    }

    private function verificarActiva(array $cfg): void
    {
        if (!($cfg['activo'] ?? true)) {
            throw new \RuntimeException('La facturación electrónica está desactivada para esta tienda. Actívala en Configuración de Facturación.');
        }
    }

    // ===================== helpers =====================

    private function comprobantePrincipal(Venta $venta): ?FacturaElectronica
    {
        return FacturaElectronica::where('venta_id', $venta->id)
            ->whereIn('tipo', ['FACTURA', 'BOLETA'])
            ->first();
    }

    private function dtoDesdeVenta(Venta $venta, array $cfg): ComprobanteDto
    {
        $tienda = $venta->tienda;

        $tipo = strtoupper($venta->tipo_comprobante) === 'FACTURA' ? 'FACTURA' : 'BOLETA';
        [$tipoDoc, $numDoc, $nombre] = $this->receptor($venta, $tipo);

        $items = [];
        foreach ($venta->detalles as $d) {
            $items[] = [
                'descripcion'    => $d->producto_nombre ?: 'PRODUCTO',
                'cantidad'       => (float) $d->cantidad,
                'valor_unitario' => (float) $d->precio_unitario,
                'codigo'         => 'P' . str_pad((string) ($d->producto_id ?? 0), 4, '0', STR_PAD_LEFT),
            ];
        }

        return new ComprobanteDto(
            pais: config('facturacion.pais', 'PE'),
            tipo: $tipo,
            moneda: 'PEN',
            serie: $venta->serie ?: ($tipo === 'FACTURA' ? 'F001' : 'B001'),
            correlativo: (int) ($venta->correlativo ?: $venta->id),
            tasaIgv: (float) (($tienda->igv ?? 18) / 100),
            emisorRuc: $cfg['emisor_ruc'],
            emisorRazonSocial: $cfg['emisor_razon'],
            emisorDireccion: $tienda->direccion ?? null,
            receptorTipoDoc: $tipoDoc,
            receptorNumDoc: $numDoc,
            receptorNombre: $nombre,
            items: $items,
            referenciaExterna: (string) $venta->id,
            fechaEmision: optional($venta->fecha)->format('Y-m-d'),
        );
    }

    private function clonarDtoComo(ComprobanteDto $b, string $tipo, string $serie, int $correlativo): ComprobanteDto
    {
        return new ComprobanteDto(
            pais: $b->pais, tipo: $tipo, moneda: $b->moneda, serie: $serie, correlativo: $correlativo,
            tasaIgv: $b->tasaIgv, emisorRuc: $b->emisorRuc, emisorRazonSocial: $b->emisorRazonSocial,
            emisorDireccion: $b->emisorDireccion, receptorTipoDoc: $b->receptorTipoDoc,
            receptorNumDoc: $b->receptorNumDoc, receptorNombre: $b->receptorNombre, items: $b->items,
            referenciaExterna: $b->referenciaExterna, fechaEmision: $b->fechaEmision,
        );
    }

    private function siguienteCorrelativo(string $tipo, string $serie): int
    {
        $max = FacturaElectronica::where('tipo', $tipo)->where('serie', $serie)->max('correlativo');
        return ((int) $max) + 1;
    }

    /** @return array{0:string,1:string,2:string} */
    private function receptor(Venta $venta, string $tipo): array
    {
        $cli = $venta->cliente;
        $nombre = $cli ? trim(($cli->nombre ?? '') . ' ' . ($cli->apellido ?? '')) : 'CLIENTE VARIOS';

        if ($tipo === 'FACTURA') {
            $ruc = $cli->dni ?? null;
            $ruc = ($ruc && strlen($ruc) === 11) ? $ruc : '20000000001';
            return ['6', $ruc, $nombre ?: 'CLIENTE DEMO SAC'];
        }
        $dni = $cli->dni ?? null;
        if ($dni && strlen($dni) === 8) {
            return ['1', $dni, $nombre ?: 'CLIENTE'];
        }
        return ['0', '00000000', $nombre ?: 'CLIENTE VARIOS'];
    }

    private function construirQr(ComprobanteDto $dto, ?string $hash): string
    {
        $gravadas = 0.0;
        foreach ($dto->items as $it) {
            $gravadas += (float) $it['valor_unitario'] * (float) $it['cantidad'];
        }
        $igv = round($gravadas * $dto->tasaIgv, 2);
        $total = round($gravadas + $igv, 2);

        return implode('|', [
            $dto->emisorRuc,
            $this->tipoSunat($dto->tipo),
            $dto->serie,
            $dto->correlativo,
            number_format($igv, 2, '.', ''),
            number_format($total, 2, '.', ''),
            $dto->fechaEmision ?: date('Y-m-d'),
            $dto->receptorTipoDoc,
            $dto->receptorNumDoc,
            $hash ?? '',
        ]);
    }

    private function tipoSunat(string $tipo): string
    {
        return match ($tipo) {
            'FACTURA'      => '01',
            'BOLETA'       => '03',
            'NOTA_CREDITO' => '07',
            'NOTA_DEBITO'  => '08',
            default        => '01',
        };
    }

    private function guardarComprobante(Venta $venta, ComprobanteDto $dto, ResultadoFacturacion $r, ?FacturaElectronica $registro, array $extra = []): FacturaElectronica
    {
        $registro ??= new FacturaElectronica();
        $registro->fill(array_merge([
            'tienda_id'   => $venta->tienda_id,
            'venta_id'    => $venta->id,
            'pais'        => $dto->pais,
            'tipo'        => $dto->tipo,
            'serie'       => $dto->serie,
            'correlativo' => $dto->correlativo,
            'estado'      => $r->estado,
            'codigo'      => $r->codigo,
            'mensaje'     => $r->mensaje,
            'id_fiscal'   => $r->idFiscal,
            'qr'          => $r->fueAceptado() ? $this->construirQr($dto, $r->idFiscal) : null,
        ], $extra));
        $registro->save();

        if ($r->xmlFirmado) {
            $registro->xml_path = $this->guardarArchivo($registro, 'firmado.xml', $r->xmlFirmado);
        }
        if ($r->cdr) {
            $registro->cdr_path = $this->guardarArchivo($registro, 'cdr.zip', base64_decode($r->cdr));
        }
        $registro->save();

        return $registro;
    }

    private function guardarArchivo(FacturaElectronica $c, string $archivo, string $contenido): string
    {
        $ruta = "facturacion/{$c->tienda_id}/{$c->tipo}-{$c->serie}-{$c->correlativo}/{$archivo}";
        Storage::disk('local')->put($ruta, $contenido);
        return $ruta;
    }
}

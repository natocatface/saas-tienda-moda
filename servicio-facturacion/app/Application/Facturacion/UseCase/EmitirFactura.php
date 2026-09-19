<?php

declare(strict_types=1);

namespace App\Application\Facturacion\UseCase;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Application\Facturacion\DTO\EmitirFacturaCommand;
use App\Application\Facturacion\DTO\ResultadoEmisionDTO;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\Emisor;
use App\Domain\Facturacion\Model\LineaComprobante;
use App\Domain\Facturacion\Model\Receptor;
use App\Domain\Facturacion\ValueObject\Dinero;
use App\Domain\Facturacion\ValueObject\DocumentoFiscal;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Moneda;
use App\Domain\Facturacion\ValueObject\Pais;
use App\Domain\Facturacion\ValueObject\TipoComprobante;

/**
 * Caso de uso: emitir una factura/boleta.
 *
 * Orquesta el dominio y los puertos. NO conoce ningún detalle de país: delega
 * en el adaptador que la fábrica elige según el país del emisor. Este mismo
 * código sirve para Perú, México, Chile, etc. sin cambios.
 */
final class EmitirFactura
{
    public function __construct(
        private readonly ComprobanteRepository $repositorio,
        private readonly FabricaProveedor $fabrica,
        private readonly RegistroAuditoria $auditoria,
        private readonly \Closure $generarId,   // inyectable: fn(): string (UUID)
    ) {}

    public function ejecutar(EmitirFacturaCommand $cmd): ResultadoEmisionDTO
    {
        $pais   = Pais::desde($cmd->pais);
        $moneda = Moneda::desde($cmd->moneda);

        // 1) Idempotencia: si la venta ya se facturó, devolver el mismo comprobante.
        $existente = $this->repositorio->porReferenciaExterna($cmd->empresaId, $cmd->referenciaExterna);
        if ($existente !== null) {
            return ResultadoEmisionDTO::desde($existente);
        }

        // 2) Construir el agregado de dominio.
        $tipo  = TipoComprobante::from($cmd->tipo);
        $serie = $cmd->serie ?? $this->seriePorDefecto($tipo);
        $correlativo = $this->repositorio->siguienteCorrelativo($cmd->empresaId, $pais->codigoIso(), $serie);

        $comprobante = new Comprobante(
            id: IdentificadorComprobante::desde(($this->generarId)()),
            pais: $pais,
            tipo: $tipo,
            serie: $serie,
            correlativo: $correlativo,
            moneda: $moneda,
            emisor: new Emisor($cmd->empresaId, new DocumentoFiscal('RUC', $cmd->emisorDocumento), $cmd->emisorRazonSocial),
            receptor: new Receptor(new DocumentoFiscal($cmd->receptorTipoDoc, $cmd->receptorDocumento), $cmd->receptorNombre),
            referenciaExterna: $cmd->referenciaExterna,
        );

        foreach ($cmd->lineas as $l) {
            $comprobante->agregarLinea(new LineaComprobante(
                descripcion: $l['descripcion'],
                cantidad: (float) $l['cantidad'],
                precioUnitario: Dinero::desde($l['precio_unitario'], $moneda),
                tasaImpuesto: (float) ($l['tasa_impuesto'] ?? 0),
                codigoProducto: $l['codigo'] ?? null,
            ));
        }

        $comprobante->validar();

        // 3) Persistir en estado RECIBIDO y auditar (permite responder 202 al ERP).
        $this->repositorio->guardar($comprobante);
        $this->auditoria->registrar(
            $comprobante->id(), 'RECEPCION', null, EstadoComprobante::RECIBIDO,
            ['referencia_externa' => $cmd->referenciaExterna],
        );

        // 4) Enviar al organismo mediante el adaptador del país (Strategy).
        //    En producción esto se encola (EnviarComprobanteJob) para no bloquear;
        //    aquí se muestra la invocación directa por claridad del flujo.
        $proveedor = $this->fabrica->para($pais);
        $resultado = $proveedor->emitirFactura($comprobante);

        // 5) Actualizar estado, persistir y auditar la respuesta.
        $comprobante->marcarEstado($resultado->estado());
        if ($resultado->fueAceptado() && $resultado->idFiscal() !== null) {
            $comprobante->marcarAceptado($resultado->idFiscal());
        }
        $this->repositorio->guardar($comprobante);
        $this->auditoria->registrar(
            $comprobante->id(), 'RESPUESTA_ORGANISMO', EstadoComprobante::RECIBIDO, $resultado->estado(),
            ['codigo' => $resultado->codigo(), 'mensaje' => $resultado->mensaje()],
        );

        return ResultadoEmisionDTO::desde($comprobante, $resultado);
    }

    private function seriePorDefecto(TipoComprobante $tipo): string
    {
        return $tipo === TipoComprobante::FACTURA ? 'F001' : 'B001';
    }
}

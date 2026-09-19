<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Peru;

use App\Domain\Facturacion\Contract\AlmacenDocumentos;
use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;
use App\Domain\Facturacion\ValueObject\TipoComprobante;

/**
 * Adaptador de referencia: PERÚ / SUNAT.
 *
 * Traduce el modelo de dominio al mundo SUNAT (UBL 2.1 → firma XML-DSig →
 * envío SOAP → lectura del CDR) y devuelve el resultado NORMALIZADO. El resto
 * del sistema no ve nada de esto.
 *
 * Flujos SUNAT que este adaptador encapsula:
 *   · Factura (01): envío individual, CDR síncrono.
 *   · Boleta (03): resumen diario → devuelve TICKET → estado EN_PROCESO,
 *     se resuelve luego con consultarEstado().
 *   · Nota de crédito (07).
 *   · Comunicación de baja (anulación de facturas).
 */
final class SunatProveedor implements ProveedorFacturacion
{
    public function __construct(
        private readonly ConstructorUbl21 $ubl,
        private readonly FirmadorXmlSunat $firmador,
        private readonly ClienteSoapSunat $cliente,
        private readonly MapeadorCdrSunat $mapeador,
        private readonly AlmacenDocumentos $almacen,
    ) {}

    public function soporta(Pais $pais): bool
    {
        return $pais->codigoIso() === 'PE';
    }

    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion
    {
        // 1) Construir el XML UBL 2.1 a partir del dominio.
        $xml = $this->ubl->construirComprobante($comprobante);

        // 2) Firmar digitalmente (XML-DSig con el certificado de la empresa).
        $xmlFirmado = $this->firmador->firmar($xml, $comprobante->emisor()->empresaId());
        $this->almacen->guardarXmlFirmado($comprobante->id(), $xmlFirmado);

        // 3) Boletas → resumen diario (asíncrono con ticket). Facturas → envío directo (CDR).
        if ($comprobante->tipo() === TipoComprobante::BOLETA) {
            $ticket = $this->cliente->enviarResumenDiario($xmlFirmado);
            return new ResultadoOperacion(
                estado: EstadoComprobante::EN_PROCESO,
                mensaje: 'Boleta enviada en resumen diario; pendiente de ticket.',
                datosProveedor: ['ticket' => $ticket],
            );
        }

        // 4) Factura: enviar y leer el CDR síncrono.
        $cdr = $this->cliente->enviarComprobante($xmlFirmado);
        $this->almacen->guardarRespuestaOrganismo($comprobante->id(), $cdr);

        return $this->mapeador->desdeCdr($cdr);
    }

    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion
    {
        $xml = $this->ubl->construirNotaCredito($notaCredito);
        $xmlFirmado = $this->firmador->firmar($xml, $notaCredito->comprobante()->emisor()->empresaId());
        $this->almacen->guardarXmlFirmado($notaCredito->comprobante()->id(), $xmlFirmado);

        $cdr = $this->cliente->enviarComprobante($xmlFirmado);
        return $this->mapeador->desdeCdr($cdr);
    }

    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion
    {
        // SUNAT: comunicación de baja → devuelve ticket → estado EN_PROCESO.
        $xml = $this->ubl->construirComunicacionBaja($solicitud);
        $xmlFirmado = $this->firmador->firmar($xml, /* empresaId */ '');
        $ticket = $this->cliente->enviarComunicacionBaja($xmlFirmado);

        return new ResultadoOperacion(
            estado: EstadoComprobante::EN_PROCESO,
            mensaje: 'Comunicación de baja enviada; pendiente de ticket.',
            datosProveedor: ['ticket' => $ticket],
        );
    }

    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion
    {
        // Consulta el estado del ticket (resumen diario / comunicación de baja).
        $cdr = $this->cliente->consultarTicket($id->valor());
        return $this->mapeador->desdeCdr($cdr);
    }
}

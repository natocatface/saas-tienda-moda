<?php

namespace App\Facturacion\Sunat;

use App\Facturacion\Contract\ProveedorFacturacion;
use App\Facturacion\Dto\ComprobanteDto;
use App\Facturacion\Dto\NotaCreditoDto;
use App\Facturacion\Dto\ResultadoFacturacion;

/**
 * Adaptador de PERÚ / SUNAT usando Greenter.
 * Firma el XML UBL 2.1 y lo envía al web service de SUNAT (beta o producción).
 */
class SunatProveedor implements ProveedorFacturacion
{
    public function __construct(
        private GreenterFactory $factory,
        private ConstructorDocumento $constructor,
        private MapeadorResultado $mapeador,
    ) {}

    public function soporta(string $pais): bool
    {
        return $pais === 'PE';
    }

    public function emitir(ComprobanteDto $comprobante): ResultadoFacturacion
    {
        try {
            $see = $this->factory->crear();
            $invoice = $this->constructor->invoice($comprobante);
            $result = $see->send($invoice);
            return $this->mapeador->desde($result, $see->getFactory()->getLastXml());
        } catch (\Throwable $e) {
            return ResultadoFacturacion::error('Error al emitir a SUNAT: ' . $e->getMessage());
        }
    }

    public function emitirNotaCredito(NotaCreditoDto $notaCredito): ResultadoFacturacion
    {
        try {
            $see = $this->factory->crear();
            $note = $this->constructor->note($notaCredito);
            $result = $see->send($note);
            return $this->mapeador->desde($result, $see->getFactory()->getLastXml());
        } catch (\Throwable $e) {
            return ResultadoFacturacion::error('Error al emitir nota de crédito: ' . $e->getMessage());
        }
    }

    public function anular(string $serieAfectada, int $correlativoAfectado, string $tipoDocAfectado, string $motivo): ResultadoFacturacion
    {
        try {
            $see = $this->factory->crear();
            // Correlativo secuencial del RA (comunicación de baja). En producción
            // conviene llevar un contador diario; aquí se usa la hora para no colisionar.
            $correlativoRa = (int) date('His');
            $voided = $this->constructor->voided($correlativoRa, $serieAfectada, $correlativoAfectado, $tipoDocAfectado, $motivo);
            $result = $see->send($voided);
            return $this->mapeador->desdeTicket($result, $see->getFactory()->getLastXml());
        } catch (\Throwable $e) {
            return ResultadoFacturacion::error('Error al comunicar la baja: ' . $e->getMessage());
        }
    }

    public function consultarEstado(string $ticket): ResultadoFacturacion
    {
        try {
            $see = $this->factory->crear();
            $status = $see->getStatus($ticket);
            return $this->mapeador->desdeStatus($status);
        } catch (\Throwable $e) {
            return ResultadoFacturacion::error('Error al consultar el estado: ' . $e->getMessage());
        }
    }
}

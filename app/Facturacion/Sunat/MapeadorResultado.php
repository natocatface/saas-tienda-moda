<?php

namespace App\Facturacion\Sunat;

use App\Facturacion\Dto\ResultadoFacturacion;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Response\StatusResult;
use Greenter\Model\Response\SummaryResult;

/**
 * Traduce las respuestas de Greenter al ResultadoFacturacion normalizado.
 * Códigos SUNAT: 0 = aceptado; 2000-3999 = rechazado; 4000+ = observado.
 */
class MapeadorResultado
{
    /** Respuesta síncrona de facturas / boletas / notas de crédito (con CDR). */
    public function desde(BillResult $result, ?string $xmlFirmado = null): ResultadoFacturacion
    {
        if (!$result->isSuccess()) {
            return $this->error($result->getError(), $xmlFirmado);
        }
        return $this->porCodigo(
            (int) $result->getCdrResponse()->getCode(),
            $result->getCdrResponse()->getDescription(),
            $xmlFirmado,
            $result->getCdrZip(),
            ['notas' => $result->getCdrResponse()->getNotes()],
        );
    }

    /** Respuesta asíncrona (comunicación de baja / resumen): devuelve un ticket. */
    public function desdeTicket(SummaryResult $result, ?string $xmlFirmado = null): ResultadoFacturacion
    {
        if (!$result->isSuccess()) {
            return $this->error($result->getError(), $xmlFirmado);
        }
        return new ResultadoFacturacion(
            estado: ResultadoFacturacion::EN_PROCESO,
            mensaje: 'Enviado a SUNAT. Consulta el estado con el ticket.',
            xmlFirmado: $xmlFirmado,
            extra: ['ticket' => $result->getTicket()],
        );
    }

    /** Resultado de consultar un ticket (getStatus). */
    public function desdeStatus(StatusResult $result): ResultadoFacturacion
    {
        if (!$result->isSuccess()) {
            return $this->error($result->getError());
        }
        return $this->porCodigo(
            (int) $result->getCode(),
            'Estado consultado.',
            null,
            $result->getCdrZip(),
            [],
        );
    }

    private function porCodigo(int $codigo, ?string $mensaje, ?string $xml, ?string $cdrZip, array $extra): ResultadoFacturacion
    {
        $estado = match (true) {
            $codigo === 0                      => ResultadoFacturacion::ACEPTADO,
            $codigo >= 2000 && $codigo <= 3999 => ResultadoFacturacion::RECHAZADO,
            $codigo >= 4000                    => ResultadoFacturacion::OBSERVADO,
            default                            => ResultadoFacturacion::ERROR,
        };

        return new ResultadoFacturacion(
            estado: $estado,
            codigo: (string) $codigo,
            mensaje: $mensaje,
            idFiscal: $estado === ResultadoFacturacion::ACEPTADO && $xml ? substr(hash('sha256', $xml), 0, 40) : null,
            xmlFirmado: $xml,
            cdr: $cdrZip ? base64_encode($cdrZip) : null,
            extra: $extra,
        );
    }

    private function error($error, ?string $xml = null): ResultadoFacturacion
    {
        return new ResultadoFacturacion(
            estado: ResultadoFacturacion::ERROR,
            codigo: $error?->getCode(),
            mensaje: $error?->getMessage() ?? 'Error desconocido en SUNAT.',
            xmlFirmado: $xml,
        );
    }
}

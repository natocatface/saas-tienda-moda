<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Peru;

use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;

/**
 * Traduce el CDR (Constancia de Recepción) de SUNAT al ResultadoOperacion
 * normalizado. Aquí vive la tabla de códigos de respuesta SUNAT:
 *   0            -> ACEPTADO
 *   100-1999     -> excepción (reintentable)
 *   2000-3999    -> RECHAZADO (error del contribuyente)
 *   4000+        -> OBSERVADO (aceptado con observaciones)
 */
final class MapeadorCdrSunat
{
    public function desdeCdr(string $cdrXml): ResultadoOperacion
    {
        // TODO: parsear el CDR real (cbc:ResponseCode, cbc:Description).
        $codigo = $this->extraerResponseCode($cdrXml);
        $descripcion = $this->extraerDescripcion($cdrXml);

        $estado = match (true) {
            $codigo === 0                       => EstadoComprobante::ACEPTADO,
            $codigo >= 2000 && $codigo <= 3999  => EstadoComprobante::RECHAZADO,
            $codigo >= 4000                     => EstadoComprobante::OBSERVADO,
            default                             => EstadoComprobante::ERROR,
        };

        return new ResultadoOperacion(
            estado: $estado,
            idFiscal: $estado === EstadoComprobante::ACEPTADO ? $this->hashCdr($cdrXml) : null,
            codigo: (string) $codigo,
            mensaje: $descripcion,
            datosProveedor: ['cdr' => $cdrXml],
        );
    }

    private function extraerResponseCode(string $cdrXml): int
    {
        // TODO: leer cbc:ResponseCode del CDR. Placeholder: aceptado.
        return 0;
    }

    private function extraerDescripcion(string $cdrXml): string
    {
        return 'La Factura numero F001-1, ha sido aceptada'; // TODO: leer del CDR real
    }

    private function hashCdr(string $cdrXml): string
    {
        return substr(hash('sha256', $cdrXml), 0, 40);
    }
}

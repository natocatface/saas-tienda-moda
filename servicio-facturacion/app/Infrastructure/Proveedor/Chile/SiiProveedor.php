<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Chile;

use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;

/**
 * CHILE / SII (stub — Fase 4).
 * Formato: DTE (XML propio) · Firma XML-DSig + CAF (folios) · Envío SOAP/REST +
 * set de pruebas. Anulación vía nota de crédito (DTE 61).
 */
final class SiiProveedor implements ProveedorFacturacion
{
    public function soporta(Pais $pais): bool
    {
        return $pais->codigoIso() === 'CL';
    }

    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion { throw $this->pendiente(); }
    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion { throw $this->pendiente(); }
    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion { throw $this->pendiente(); }
    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion { throw $this->pendiente(); }

    private function pendiente(): FacturacionException
    {
        return new FacturacionException(
            'Adaptador SII (Chile) pendiente de implementación (Fase 4).',
            FacturacionException::CAT_CONFIGURACION,
            'SII_NO_IMPLEMENTADO',
        );
    }
}

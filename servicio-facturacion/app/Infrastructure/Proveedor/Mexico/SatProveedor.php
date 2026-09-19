<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Mexico;

use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;

/**
 * MÉXICO / SAT vía PAC (stub — Fase 4).
 * Formato: CFDI 4.0 (XML) · Sello con CSD · TIMBRADO por un PAC (Proveedor
 * Autorizado de Certificación) que devuelve el UUID (timbre fiscal digital).
 * Este adaptador integra con el PAC, no con el SAT directamente.
 * Cancelación con acuse/aceptación del receptor.
 */
final class SatProveedor implements ProveedorFacturacion
{
    public function soporta(Pais $pais): bool
    {
        return $pais->codigoIso() === 'MX';
    }

    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion { throw $this->pendiente(); }
    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion { throw $this->pendiente(); }
    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion { throw $this->pendiente(); }
    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion { throw $this->pendiente(); }

    private function pendiente(): FacturacionException
    {
        return new FacturacionException(
            'Adaptador SAT/PAC (México) pendiente de implementación (Fase 4).',
            FacturacionException::CAT_CONFIGURACION,
            'SAT_NO_IMPLEMENTADO',
        );
    }
}

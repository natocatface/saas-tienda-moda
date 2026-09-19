<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Colombia;

use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;

/**
 * COLOMBIA / DIAN (stub — Fase 3).
 * Formato: UBL 2.1 (perfil DIAN) · Firma XML-DSig + CUFE · Validación previa REST.
 * Al implementarse, se agregan colaboradores ConstructorUblDian, GeneradorCufe,
 * ClienteRestDian, MapeadorRespuestaDian — todos dentro de esta carpeta.
 */
final class DianProveedor implements ProveedorFacturacion
{
    public function soporta(Pais $pais): bool
    {
        return $pais->codigoIso() === 'CO';
    }

    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion
    {
        throw $this->pendiente();
    }

    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion
    {
        throw $this->pendiente();
    }

    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion
    {
        throw $this->pendiente();
    }

    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion
    {
        throw $this->pendiente();
    }

    private function pendiente(): FacturacionException
    {
        return new FacturacionException(
            'Adaptador DIAN (Colombia) pendiente de implementación (Fase 3).',
            FacturacionException::CAT_CONFIGURACION,
            'DIAN_NO_IMPLEMENTADO',
        );
    }
}

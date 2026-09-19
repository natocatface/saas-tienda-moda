<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Argentina;

use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;

/**
 * ARGENTINA / ARCA (ex-AFIP) (stub — Fase 4).
 * Sin XML propio del contribuyente: autenticación WSAA (token+sign) y llamada
 * SOAP a WSFEv1 (FECAESolicitar) que devuelve el CAE/CAEA de forma inmediata.
 * El PDF se genera a partir del CAE. Notas de crédito = comprobantes 3/8/13.
 */
final class ArcaProveedor implements ProveedorFacturacion
{
    public function soporta(Pais $pais): bool
    {
        return $pais->codigoIso() === 'AR';
    }

    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion { throw $this->pendiente(); }
    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion { throw $this->pendiente(); }
    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion { throw $this->pendiente(); }
    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion { throw $this->pendiente(); }

    private function pendiente(): FacturacionException
    {
        return new FacturacionException(
            'Adaptador ARCA (Argentina) pendiente de implementación (Fase 4).',
            FacturacionException::CAT_CONFIGURACION,
            'ARCA_NO_IMPLEMENTADO',
        );
    }
}

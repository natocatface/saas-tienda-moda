<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Peru;

use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\SolicitudAnulacion;

/**
 * Construye el XML UBL 2.1 (CPE) que exige SUNAT a partir del modelo de dominio.
 * Aquí se mapean TipoComprobante -> códigos SUNAT (01/03/07/08), catálogos de
 * impuestos (IGV 1000), unidades de medida, etc.
 *
 * Recomendación de implementación: usar la librería `greenter/greenter` o
 * `sunat/facturacion`, o plantillas XML propias. Este archivo AÍSLA esa
 * decisión: si mañana se cambia de librería, solo se toca esta clase.
 */
final class ConstructorUbl21
{
    public function construirComprobante(Comprobante $comprobante): string
    {
        // TODO: mapear dominio -> UBL 2.1 (Invoice). Ejemplo con Greenter:
        //   $invoice = (new Invoice())->setTipoDoc($this->tipoSunat($comprobante->tipo()))
        //       ->setSerie($comprobante->serie())->setCorrelativo((string)$comprobante->correlativo())
        //       ->setMtoOperGravadas($comprobante->totalGravado()->monto())
        //       ->setMtoIGV($comprobante->totalImpuestos()->monto()) ...;
        //   return (new XmlBuilder())->build($invoice);
        return '<Invoke>TODO-UBL-2.1</Invoke>';
    }

    public function construirNotaCredito(NotaCredito $notaCredito): string
    {
        // TODO: UBL 2.1 CreditNote (tipo 07) referenciando serie/correlativo afectado.
        return '<CreditNote>TODO</CreditNote>';
    }

    public function construirComunicacionBaja(SolicitudAnulacion $solicitud): string
    {
        // TODO: UBL VoidedDocuments (comunicación de baja de facturas).
        return '<VoidedDocuments>TODO</VoidedDocuments>';
    }

    /** Mapea el tipo de dominio al código de catálogo SUNAT (01, 03, 07, 08). */
    private function tipoSunat(\App\Domain\Facturacion\ValueObject\TipoComprobante $tipo): string
    {
        return match ($tipo) {
            \App\Domain\Facturacion\ValueObject\TipoComprobante::FACTURA      => '01',
            \App\Domain\Facturacion\ValueObject\TipoComprobante::BOLETA       => '03',
            \App\Domain\Facturacion\ValueObject\TipoComprobante::NOTA_CREDITO => '07',
            \App\Domain\Facturacion\ValueObject\TipoComprobante::NOTA_DEBITO  => '08',
        };
    }
}

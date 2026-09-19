<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Contract;

use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\Model\ResultadoOperacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Pais;

/**
 * Contrato ÚNICO de facturación electrónica (patrón Strategy/Adapter).
 *
 * Cada país (SUNAT, DIAN, SII, ARCA, SAT...) implementa esta interfaz en su
 * propio adaptador dentro de Infrastructure/Proveedor/{Pais}/. El resto del
 * sistema (casos de uso, API, ERP) SOLO conoce esta interfaz: nunca ve un XML
 * UBL, un CDR, un CAE ni un UUID. Esos detalles viajan dentro de
 * ResultadoOperacion::datosProveedor().
 *
 * Agregar un país nuevo = crear una clase que implemente esta interfaz y
 * registrarla en config/facturacion.php. No se modifica ninguna clase existente.
 */
interface ProveedorFacturacion
{
    /** ¿Este adaptador atiende al país indicado? (lo usa la fábrica). */
    public function soporta(Pais $pais): bool;

    /** Emite una factura o boleta y devuelve el resultado normalizado del organismo. */
    public function emitirFactura(Comprobante $comprobante): ResultadoOperacion;

    /** Emite una nota de crédito asociada a un comprobante previo. */
    public function emitirNotaCredito(NotaCredito $notaCredito): ResultadoOperacion;

    /** Anula / da de baja / cancela un comprobante ya emitido. */
    public function anularFactura(SolicitudAnulacion $solicitud): ResultadoOperacion;

    /** Consulta el estado actual del comprobante ante el organismo. */
    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion;
}

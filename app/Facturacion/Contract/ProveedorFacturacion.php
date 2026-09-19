<?php

namespace App\Facturacion\Contract;

use App\Facturacion\Dto\ComprobanteDto;
use App\Facturacion\Dto\NotaCreditoDto;
use App\Facturacion\Dto\ResultadoFacturacion;

/**
 * Contrato único de facturación electrónica (patrón Strategy/Adapter).
 * Cada país implementa esta interfaz en app/Facturacion/{Organismo}/.
 * Agregar un país = nueva clase + una rama en App\Facturacion\FabricaProveedor.
 */
interface ProveedorFacturacion
{
    public function soporta(string $pais): bool;

    /** Emite una factura o boleta. */
    public function emitir(ComprobanteDto $comprobante): ResultadoFacturacion;

    /** Emite una nota de crédito que afecta a un comprobante previo. */
    public function emitirNotaCredito(NotaCreditoDto $notaCredito): ResultadoFacturacion;

    /** Comunicación de baja / anulación de un comprobante (devuelve ticket asíncrono). */
    public function anular(string $serieAfectada, int $correlativoAfectado, string $tipoDocAfectado, string $motivo): ResultadoFacturacion;

    /** Consulta el estado de un ticket (baja / resumen). */
    public function consultarEstado(string $ticket): ResultadoFacturacion;
}

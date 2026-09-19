<?php

namespace App\Facturacion\Dto;

/**
 * Datos para emitir una nota de crédito (tipo 07) que afecta a un comprobante
 * previo. Reutiliza un ComprobanteDto (emisor, receptor, items) y agrega la
 * referencia al documento afectado y el motivo (catálogo 09 SUNAT).
 */
class NotaCreditoDto
{
    public function __construct(
        public ComprobanteDto $comprobante,   // con tipo = 'NOTA_CREDITO' y su propia serie/correlativo
        public string $tipoDocAfectado,        // '01' factura, '03' boleta
        public string $numDocAfectado,         // 'F001-1'
        public string $codMotivo = '01',       // 01 = Anulación de la operación
        public string $desMotivo = 'ANULACION DE LA OPERACION',
    ) {}
}

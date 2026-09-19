<?php

namespace App\Facturacion\Dto;

/**
 * Datos de un comprobante en lenguaje de negocio, agnósticos del país.
 * Se construye desde una Venta del ERP (ver ServicioFacturacion) y se pasa al
 * adaptador del país correspondiente. Ningún dato de SUNAT/UBL aparece aquí.
 */
class ComprobanteDto
{
    /**
     * @param array<int,array{descripcion:string,cantidad:float,valor_unitario:float,codigo?:string}> $items
     */
    public function __construct(
        public string $pais,               // 'PE'
        public string $tipo,               // 'FACTURA' | 'BOLETA' | 'NOTA_CREDITO'
        public string $moneda,             // 'PEN'
        public string $serie,              // 'F001' / 'B001'
        public int    $correlativo,
        public float  $tasaIgv,            // 0.18
        // Emisor
        public string $emisorRuc,
        public string $emisorRazonSocial,
        public ?string $emisorDireccion,
        // Receptor
        public string $receptorTipoDoc,    // '6'=RUC, '1'=DNI, '0'=sin doc
        public string $receptorNumDoc,
        public string $receptorNombre,
        // Detalle
        public array  $items,
        public string $referenciaExterna,  // id de la venta en el ERP
        public ?string $fechaEmision = null, // Y-m-d
    ) {}
}

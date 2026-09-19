<?php

declare(strict_types=1);

namespace App\Application\Facturacion\DTO;

/**
 * Comando de entrada para emitir una factura/boleta. Es un DTO plano que viene
 * del controlador (mapeado desde el JSON del ERP). No contiene lógica.
 *
 * @param array<int,array{descripcion:string,cantidad:float,precio_unitario:float,tasa_impuesto:float,codigo?:string}> $lineas
 */
final class EmitirFacturaCommand
{
    public function __construct(
        public readonly string $pais,
        public readonly string $tipo,
        public readonly string $moneda,
        public readonly string $empresaId,
        public readonly string $emisorDocumento,
        public readonly string $emisorRazonSocial,
        public readonly string $receptorTipoDoc,
        public readonly string $receptorDocumento,
        public readonly string $receptorNombre,
        public readonly string $referenciaExterna,
        public readonly array $lineas,
        public readonly ?string $serie = null,
    ) {}
}

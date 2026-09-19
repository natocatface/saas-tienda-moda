<?php

namespace App\Facturacion;

use App\Facturacion\Contract\ProveedorFacturacion;
use App\Facturacion\Sunat\ConstructorDocumento;
use App\Facturacion\Sunat\GreenterFactory;
use App\Facturacion\Sunat\MapeadorResultado;
use App\Facturacion\Sunat\SunatProveedor;

/**
 * Selecciona el adaptador de facturación según el país, inyectándole la
 * configuración efectiva (por tienda) resuelta por ServicioFacturacion.
 * Agregar un país nuevo = añadir su rama aquí y su clase adaptadora.
 */
class FabricaProveedor
{
    /** @param array $sunatConfig ['modo','ruc','sol_usuario','sol_clave','certificado'] */
    public function para(string $pais, array $sunatConfig = []): ProveedorFacturacion
    {
        return match ($pais) {
            'PE' => new SunatProveedor(
                new GreenterFactory($sunatConfig),
                new ConstructorDocumento(),
                new MapeadorResultado(),
            ),
            // 'CO' => new DianProveedor(...),
            // 'CL' => new SiiProveedor(...),
            default => throw new \RuntimeException("País de facturación no soportado: {$pais}"),
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Domain\Facturacion\Contract\AlmacenDocumentos;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Infrastructure\Almacenamiento\AlmacenDocumentosDisco;
use App\Infrastructure\Auditoria\RegistroAuditoriaBd;
use App\Infrastructure\Persistence\Eloquent\ComprobanteRepositoryEloquent;
use App\Application\Facturacion\UseCase\EmitirFactura;
use App\Infrastructure\Proveedor\FabricaProveedorFacturacion;
use App\Infrastructure\Proveedor\Peru\ClienteSoapSunat;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Enlaza los PUERTOS del dominio con sus implementaciones de infraestructura.
 * Es el "cableado" (composition root): aquí y solo aquí se decide qué clase
 * concreta satisface cada interfaz. El dominio nunca conoce estas clases.
 */
final class FacturacionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Puertos -> Adaptadores
        $this->app->bind(ComprobanteRepository::class, ComprobanteRepositoryEloquent::class);
        $this->app->bind(AlmacenDocumentos::class, AlmacenDocumentosDisco::class);
        $this->app->bind(RegistroAuditoria::class, RegistroAuditoriaBd::class);

        // Fábrica de proveedores (inyecta el mapa país->clase del config)
        $this->app->bind(FabricaProveedor::class, function ($app) {
            return new FabricaProveedorFacturacion(
                $app,                                   // contenedor PSR-11
                config('facturacion.proveedores', []),
            );
        });

        // Ejemplo de configuración de un adaptador concreto (SUNAT)
        $this->app->bind(ClienteSoapSunat::class, function () {
            $cfg = config('facturacion.paises.PE', []);
            return new ClienteSoapSunat($cfg['endpoint_factura'] ?? '', (bool) ($cfg['modo_beta'] ?? true));
        });

        // El caso de uso EmitirFactura recibe un generador de IDs (UUID) como
        // dependencia explícita; el contenedor no puede autoresolver un Closure.
        $this->app->bind(EmitirFactura::class, function ($app) {
            return new EmitirFactura(
                $app->make(ComprobanteRepository::class),
                $app->make(FabricaProveedor::class),
                $app->make(RegistroAuditoria::class),
                fn (): string => (string) Str::uuid(),
            );
        });
    }
}

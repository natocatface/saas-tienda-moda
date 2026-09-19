# Servicio de Facturación Electrónica (SFE)

Microservicio independiente que dota al ERP *Tienda Moda* de facturación electrónica **multi-país** (Perú/SUNAT, Colombia/DIAN, Chile/SII, Argentina/ARCA, México/SAT) tras un **contrato único**.

> Documento de arquitectura completo: [`../docs/ARQUITECTURA_FACTURACION_ELECTRONICA.md`](../docs/ARQUITECTURA_FACTURACION_ELECTRONICA.md)

Este directorio es un **scaffold**: la estructura, las interfaces y el adaptador de Perú de referencia están escritos; la lógica real de cada organismo (UBL, firma, SOAP) está marcada con `TODO`.

---

## Principio central

El ERP habla un solo idioma de negocio. El SFE traduce a cada organismo mediante un **adaptador por país** elegido en tiempo de ejecución. **Agregar un país no modifica ningún código existente**: se crea una carpeta en `Infrastructure/Proveedor/{Pais}/` y se añade una línea en `config/facturacion.php`.

```
ERP ──REST/JSON──▶ SFE (contrato único) ──▶ Fábrica ──▶ Adaptador país ──▶ Organismo
                        EmitirFactura · AnularFactura · EmitirNotaCredito · ConsultarEstado
```

## Capas (Clean Architecture)

| Capa | Carpeta | Conoce a... |
|------|---------|-------------|
| **Domain** | `app/Domain/Facturacion` | nada externo (PHP puro) |
| **Application** | `app/Application/Facturacion` | Domain |
| **Infrastructure** | `app/Infrastructure` | Domain + Application (implementa puertos) |
| **Interfaces** | `app/Http`, `app/Jobs` | Application |

La regla de dependencia apunta siempre hacia el Domain.

## El contrato (la pieza clave)

`app/Domain/Facturacion/Contract/ProveedorFacturacion.php`

```php
interface ProveedorFacturacion {
    public function soporta(Pais $pais): bool;
    public function emitirFactura(Comprobante $c): ResultadoOperacion;
    public function emitirNotaCredito(NotaCredito $nc): ResultadoOperacion;
    public function anularFactura(SolicitudAnulacion $s): ResultadoOperacion;
    public function consultarEstado(IdentificadorComprobante $id): ResultadoOperacion;
}
```

## Cómo agregar un país nuevo (ej. Ecuador/SRI)

1. Crear `app/Infrastructure/Proveedor/Ecuador/SriProveedor.php` que `implements ProveedorFacturacion`.
2. Añadir sus colaboradores (builder, firmador, cliente, mapeador) en esa misma carpeta.
3. Registrar en `config/facturacion.php`:
   ```php
   'EC' => App\Infrastructure\Proveedor\Ecuador\SriProveedor::class,
   ```
4. Cargar sus credenciales en `credenciales_pais`.

**No se toca** ningún caso de uso, controlador ni adaptador existente.

## Estructura

```
app/
├── Domain/Facturacion/        Model · ValueObject · Contract (puertos) · Exception
├── Application/Facturacion/   UseCase · DTO · Contract
├── Infrastructure/
│   ├── Proveedor/             FabricaProveedorFacturacion + {Peru,Colombia,Chile,Argentina,Mexico}/
│   ├── Persistence/Eloquent/  repositorio
│   ├── Almacenamiento/        XML/PDF (disco/S3)
│   └── Auditoria/             log inmutable
├── Http/Controllers/Api/V1/   API REST
├── Jobs/                      envío asíncrono + reintentos
└── Providers/                 cableado puertos->implementaciones
config/facturacion.php         mapa país->adaptador, reintentos, endpoints
database/migrations/           comprobantes, líneas, eventos, credenciales
routes/api.php                 /api/v1/facturas ...
```

## Puesta en marcha (cuando se implemente sobre Laravel)

```bash
composer create-project laravel/laravel servicio-facturacion   # si se parte de cero
# copiar app/, config/, database/, routes/ de este scaffold
php artisan migrate
php artisan queue:work        # procesa EnviarComprobanteJob (reintentos)
```

Registrar `FacturacionServiceProvider` en `config/app.php` (o `bootstrap/providers.php` en Laravel 11+).

## Estados normalizados

`RECIBIDO → EN_PROCESO → ACEPTADO | OBSERVADO | RECHAZADO | ANULADO | ERROR`

Cada adaptador mapea los códigos de su organismo (CDR, CAE, UUID, CUFE) a este enum común.

## Hoja de ruta

- **Fase 0** — Fundaciones (este scaffold + adaptador *fake*).
- **Fase 1** — Perú/SUNAT productivo (país de referencia).
- **Fase 2** — Endurecimiento (reintentos, circuit breaker, observabilidad).
- **Fase 3** — Colombia/DIAN (valida el patrón "agregar país sin tocar lo existente").
- **Fase 4** — Chile/SII · México/SAT · Argentina/ARCA (paralelizable).
- **Fase 5** — Escala, multi-PAC, bus de eventos, nuevos países.

## Librerías sugeridas por país

| País | Sugerencia |
|------|------------|
| Perú | `greenter/greenter` (UBL 2.1, firma, SOAP SUNAT/OSE) |
| Colombia | UBL 2.1 perfil DIAN + generación de CUFE |
| Chile | `libredte/libredte-lib` (DTE, CAF) |
| Argentina | cliente SOAP `WSFEv1` + WSAA (token) |
| México | SDK de un PAC (Finkok, Facturama, etc.) para CFDI 4.0 |
```

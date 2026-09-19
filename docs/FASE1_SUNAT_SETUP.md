# Fase 1 — Facturación electrónica SUNAT (Perú) · Guía de puesta en marcha

Módulo integrado en el ERP que emite facturas y boletas al **ambiente beta de SUNAT** usando [Greenter](https://greenter.dev). Con esta guía lo dejas funcionando end-to-end en Laragon.

---

## 1. Requisitos (extensiones PHP)

Greenter necesita estas extensiones (Laragon normalmente ya las trae; verifica en `php.ini`):

```
extension=soap
extension=openssl
extension=zip
extension=mbstring
extension=curl
```

Reinicia Apache/Nginx en Laragon tras habilitarlas. Verifica con `php -m`.

## 2. Instalar Greenter

Ya está declarado en `composer.json`. En la carpeta del proyecto:

```bash
composer install
# o, si el proyecto ya tenía vendor/:
composer require greenter/greenter:^5.1
```

## 3. Certificado para el ambiente beta

SUNAT **beta acepta certificados autofirmados**. Genera uno (necesitas OpenSSL, incluido en Laragon) y colócalo donde el sistema lo espera:

```bash
# Desde la carpeta del proyecto:
mkdir -p storage/app/certificates
cd storage/app/certificates

openssl req -x509 -newkey rsa:2048 -days 365 -nodes ^
  -keyout key.pem -out cert.pem ^
  -subj "/C=PE/ST=Lima/L=Lima/O=EMPRESA DEMO/CN=DEMO"

# Greenter espera el certificado + la llave en un solo archivo PEM:
type cert.pem key.pem > certificate.pem
```

> En Windows CMD usa `^` para continuar línea (como arriba) y `type a b > c` para concatenar.
> En Git Bash/PowerShell usa `\` y `cat cert.pem key.pem > certificate.pem`.

Alternativa: descargar el certificado demo público de Greenter y guardarlo como `storage/app/certificates/certificate.pem`.

## 4. Variables de entorno (.env)

Agrega (los valores por defecto ya son los de prueba de SUNAT, así que basta con esto):

```dotenv
FACTURACION_HABILITADO=true
FACTURACION_AUTO=false          # true = factura sola al registrar la venta

SUNAT_MODO=beta_demo            # beta_demo | beta | produccion
SUNAT_RUC=20000000001           # RUC de pruebas de SUNAT
SUNAT_SOL_USER=MODDATOS
SUNAT_SOL_PASS=moddatos
# SUNAT_CERT=storage/app/certificates/certificate.pem   (por defecto)
```

## 5. Migración

```bash
php artisan migrate      # crea la tabla facturas_electronicas
```

## 6. Probar el flujo completo

1. Inicia sesión y registra una **venta** (POS → Nueva Venta).
2. Abre el comprobante de esa venta (Historial de Ventas → ver).
3. En la tarjeta **“Facturación Electrónica”**, pulsa **“Enviar a SUNAT”**.
4. Si todo está bien, verás **“Aceptado por SUNAT”** con **código 0** y podrás descargar el **XML firmado** y el **CDR**.

En modo `beta_demo` el emisor se envía con el RUC de prueba `20000000001` (requisito del ambiente beta), independientemente del RUC de tu tienda.

---

## Qué hace y qué falta

**Implementado (Fase 1):** emisión de **facturas y boletas** (UBL 2.1, firma, envío SOAP, lectura del CDR), guardado de XML/CDR, estado normalizado y tarjeta en la venta.

**Implementado (Fase 1.1):**
- **Nota de crédito** (tipo 07): desde el comprobante aceptado, botón "Emitir Nota de Crédito" con motivo. Síncrono, devuelve CDR.
- **Comunicación de baja** de facturas (RA): botón "Comunicar baja" → SUNAT devuelve un **ticket**; luego "Consultar estado de baja" resuelve con `getStatus` y deja el comprobante **ANULADO**.
- Nueva migración `..._facturas_electronicas_fase11` (columnas motivo, ticket, documento_afectado_id y varios comprobantes por venta). **Vuelve a correr `php artisan migrate`** para aplicarla.

**Implementado (Fase 1.2):**
- **Representación impresa con código QR de SUNAT**: botón "Imprimir" en cada comprobante y nota de crédito. Abre una página lista para imprimir o **Guardar como PDF** desde el navegador (sin binarios externos); el QR se arma con el formato oficial de SUNAT. Nueva migración `..._add_qr_to_facturas_electronicas` → **corre `php artisan migrate`** otra vez.

**Pendiente (siguientes fases):**
- Resumen diario de boletas para **producción** (en beta se envían individualmente).
- Notas de débito.
- En producción: **tu** certificado y credenciales SOL reales.

## Pasar a producción (más adelante)

1. `SUNAT_MODO=produccion`.
2. `SUNAT_CERT` apuntando a **tu** certificado digital real (.pem con llave).
3. `SUNAT_RUC`, `SUNAT_SOL_USER`, `SUNAT_SOL_PASS` con **tus** credenciales SOL.
4. Registrar series autorizadas y activar el resumen diario de boletas.

## Arquitectura

Este módulo respeta el diseño de [`ARQUITECTURA_FACTURACION_ELECTRONICA.md`](ARQUITECTURA_FACTURACION_ELECTRONICA.md):
la interfaz `App\Facturacion\Contract\ProveedorFacturacion` es el contrato único; `SunatProveedor` es el adaptador de Perú. Para agregar otro país se crea su adaptador y se añade al `match` de `App\Facturacion\FabricaProveedor` — sin tocar el resto del ERP.

## Solución de problemas

| Síntoma | Causa / arreglo |
|---------|-----------------|
| `Certificado no encontrado` | Falta `storage/app/certificates/certificate.pem` (paso 3). |
| `Class "SoapClient" not found` | Habilita `extension=soap` en php.ini y reinicia. |
| `0161` / rechazo de firma | El PEM no incluye la llave privada; concaténala (paso 3). |
| Timeout / sin respuesta | SUNAT beta intermitente; reintenta con el botón. |
| Código 2xxx-3xxx | Rechazo fiscal (datos del comprobante); revisa el mensaje del CDR. |

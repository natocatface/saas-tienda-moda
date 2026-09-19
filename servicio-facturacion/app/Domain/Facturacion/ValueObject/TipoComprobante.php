<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

/**
 * Tipo de documento en lenguaje de dominio (agnóstico de país).
 * El adaptador de cada país lo traduce a su código local:
 *   SUNAT: FACTURA=01, BOLETA=03, NOTA_CREDITO=07, NOTA_DEBITO=08
 *   CFDI:  FACTURA=I (ingreso), NOTA_CREDITO=E (egreso)
 *   ARCA:  FACTURA=1/6/11, NOTA_CREDITO=3/8/13 ...
 */
enum TipoComprobante: string
{
    case FACTURA      = 'FACTURA';
    case BOLETA       = 'BOLETA';
    case NOTA_CREDITO = 'NOTA_CREDITO';
    case NOTA_DEBITO  = 'NOTA_DEBITO';
}

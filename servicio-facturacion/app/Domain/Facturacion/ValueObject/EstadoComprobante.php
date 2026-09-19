<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

/**
 * Estados normalizados de dominio. Cada adaptador de país mapea los códigos
 * de su organismo (CDR SUNAT, CAE ARCA, UUID SAT, CUFE DIAN...) a uno de estos.
 */
enum EstadoComprobante: string
{
    case RECIBIDO   = 'RECIBIDO';    // el SFE aceptó la solicitud, aún no enviada
    case EN_PROCESO = 'EN_PROCESO';  // enviada; el organismo responde diferido (ticket/lote)
    case ACEPTADO   = 'ACEPTADO';    // aceptado por el organismo
    case OBSERVADO  = 'OBSERVADO';   // aceptado con observaciones no bloqueantes
    case RECHAZADO  = 'RECHAZADO';   // rechazado por errores fiscales (requiere corrección)
    case ANULADO    = 'ANULADO';     // dado de baja / cancelado
    case ERROR      = 'ERROR';       // fallo técnico (reintentable)

    /** ¿Es un estado final (no cambiará solo)? */
    public function esFinal(): bool
    {
        return in_array($this, [self::ACEPTADO, self::OBSERVADO, self::RECHAZADO, self::ANULADO], true);
    }

    /** ¿Amerita reintento automático? */
    public function esReintentable(): bool
    {
        return $this === self::ERROR;
    }
}

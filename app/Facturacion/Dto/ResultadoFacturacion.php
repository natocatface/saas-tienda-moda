<?php

namespace App\Facturacion\Dto;

/**
 * Resultado normalizado de una operación fiscal, común a todos los países.
 * Los datos crudos del organismo (CDR, XML) viajan en $extra.
 */
class ResultadoFacturacion
{
    /** Estados normalizados. */
    public const ACEPTADO  = 'ACEPTADO';
    public const OBSERVADO = 'OBSERVADO';
    public const RECHAZADO = 'RECHAZADO';
    public const EN_PROCESO = 'EN_PROCESO';
    public const ERROR     = 'ERROR';

    public function __construct(
        public string  $estado,
        public ?string $codigo = null,       // código del organismo (0 = aceptado en SUNAT)
        public ?string $mensaje = null,
        public ?string $idFiscal = null,     // hash del CDR / CAE / UUID
        public ?string $xmlFirmado = null,   // contenido del XML firmado
        public ?string $cdr = null,          // contenido del CDR (zip base64 o xml)
        public array   $extra = [],
    ) {}

    public function fueAceptado(): bool
    {
        return in_array($this->estado, [self::ACEPTADO, self::OBSERVADO], true);
    }

    public static function error(string $mensaje, ?string $codigo = null): self
    {
        return new self(self::ERROR, $codigo, $mensaje);
    }
}

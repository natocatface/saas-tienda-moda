<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Exception;

use RuntimeException;

/**
 * Excepción base de facturación. Lleva una categoría que decide el tratamiento
 * (reintentar, corregir, rechazar) y un código normalizado del SFE.
 */
class FacturacionException extends RuntimeException
{
    public const CAT_VALIDACION    = 'VALIDACION';
    public const CAT_NEGOCIO       = 'NEGOCIO';       // rechazo fiscal del organismo
    public const CAT_COMUNICACION  = 'COMUNICACION';  // técnico → reintentable
    public const CAT_CONFIGURACION = 'CONFIGURACION';

    public function __construct(
        string $mensaje,
        private readonly string $categoria = self::CAT_NEGOCIO,
        private readonly ?string $codigoSfe = null,
        private readonly array $detalleProveedor = [],
    ) {
        parent::__construct($mensaje);
    }

    public function categoria(): string { return $this->categoria; }
    public function codigoSfe(): ?string { return $this->codigoSfe; }
    public function detalleProveedor(): array { return $this->detalleProveedor; }

    public function esReintentable(): bool
    {
        return $this->categoria === self::CAT_COMUNICACION;
    }
}

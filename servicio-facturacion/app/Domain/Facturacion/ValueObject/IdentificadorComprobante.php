<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

/** Identificador interno del comprobante en el SFE (UUID). */
final class IdentificadorComprobante
{
    private function __construct(private readonly string $valor) {}

    public static function desde(string $valor): self
    {
        return new self($valor);
    }

    public function valor(): string
    {
        return $this->valor;
    }
}

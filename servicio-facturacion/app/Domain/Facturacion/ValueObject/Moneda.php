<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

use InvalidArgumentException;

/** Moneda ISO-4217. */
final class Moneda
{
    private const VALIDAS = ['PEN', 'COP', 'CLP', 'ARS', 'MXN', 'USD'];

    private function __construct(private readonly string $codigo) {}

    public static function desde(string $codigo): self
    {
        $c = strtoupper(trim($codigo));
        if (!in_array($c, self::VALIDAS, true)) {
            throw new InvalidArgumentException("Moneda no válida: {$codigo}");
        }
        return new self($c);
    }

    public function codigo(): string
    {
        return $this->codigo;
    }

    public function equals(Moneda $otra): bool
    {
        return $this->codigo === $otra->codigo;
    }
}

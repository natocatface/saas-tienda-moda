<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

use InvalidArgumentException;

/**
 * Importe monetario. Guarda el monto en céntimos (int) para evitar errores de
 * coma flotante. Inmutable; la aritmética devuelve nuevas instancias.
 */
final class Dinero
{
    private function __construct(
        private readonly int $centimos,
        private readonly Moneda $moneda,
    ) {}

    public static function desde(float|string $monto, Moneda $moneda): self
    {
        $centimos = (int) round(((float) $monto) * 100);
        return new self($centimos, $moneda);
    }

    public static function cero(Moneda $moneda): self
    {
        return new self(0, $moneda);
    }

    public function sumar(Dinero $otro): self
    {
        $this->mismaMoneda($otro);
        return new self($this->centimos + $otro->centimos, $this->moneda);
    }

    public function multiplicarPor(float $factor): self
    {
        return new self((int) round($this->centimos * $factor), $this->moneda);
    }

    public function monto(): float
    {
        return $this->centimos / 100;
    }

    public function moneda(): Moneda
    {
        return $this->moneda;
    }

    public function equals(Dinero $otro): bool
    {
        return $this->centimos === $otro->centimos && $this->moneda->equals($otro->moneda);
    }

    private function mismaMoneda(Dinero $otro): void
    {
        if (!$this->moneda->equals($otro->moneda)) {
            throw new InvalidArgumentException('No se pueden operar importes de distinta moneda.');
        }
    }
}

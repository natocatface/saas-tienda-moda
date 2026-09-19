<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\ValueObject\Dinero;
use InvalidArgumentException;

/** Línea de detalle (ítem) de un comprobante. */
final class LineaComprobante
{
    public function __construct(
        private readonly string $descripcion,
        private readonly float $cantidad,
        private readonly Dinero $precioUnitario,
        private readonly float $tasaImpuesto,   // 0.18 = 18%
        private readonly ?string $codigoProducto = null,
        private readonly ?string $unidadMedida = 'NIU',
    ) {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad debe ser mayor que cero.');
        }
        if ($tasaImpuesto < 0) {
            throw new InvalidArgumentException('La tasa de impuesto no puede ser negativa.');
        }
    }

    public function descripcion(): string { return $this->descripcion; }
    public function cantidad(): float { return $this->cantidad; }
    public function precioUnitario(): Dinero { return $this->precioUnitario; }
    public function tasaImpuesto(): float { return $this->tasaImpuesto; }
    public function codigoProducto(): ?string { return $this->codigoProducto; }
    public function unidadMedida(): ?string { return $this->unidadMedida; }

    /** Base gravable de la línea (sin impuesto). */
    public function baseGravable(): Dinero
    {
        return $this->precioUnitario->multiplicarPor($this->cantidad);
    }

    /** Impuesto de la línea. */
    public function impuesto(): Dinero
    {
        return $this->baseGravable()->multiplicarPor($this->tasaImpuesto);
    }

    /** Total de la línea (base + impuesto). */
    public function total(): Dinero
    {
        return $this->baseGravable()->sumar($this->impuesto());
    }
}

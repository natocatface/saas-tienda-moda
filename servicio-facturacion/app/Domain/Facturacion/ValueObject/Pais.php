<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

use InvalidArgumentException;

/**
 * País emisor en formato ISO-3166 alpha-2. Value object inmutable.
 * Solo reconoce países con soporte planificado; agregar uno es añadirlo aquí
 * y crear su adaptador.
 */
final class Pais
{
    private const SOPORTADOS = ['PE', 'CO', 'CL', 'AR', 'MX'];

    private function __construct(private readonly string $codigo) {}

    public static function desde(string $codigoIso): self
    {
        $codigo = strtoupper(trim($codigoIso));
        if (!in_array($codigo, self::SOPORTADOS, true)) {
            throw new InvalidArgumentException("País no soportado: {$codigoIso}");
        }
        return new self($codigo);
    }

    public function codigoIso(): string
    {
        return $this->codigo;
    }

    public function equals(Pais $otro): bool
    {
        return $this->codigo === $otro->codigo;
    }
}

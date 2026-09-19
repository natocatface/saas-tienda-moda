<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\ValueObject;

use InvalidArgumentException;

/**
 * Documento fiscal del emisor/receptor (RUC, NIT, RUT, CUIT, RFC).
 * La validación de FORMATO específica de cada país la hace el adaptador
 * correspondiente; aquí solo se valida que no esté vacío y su tipo.
 */
final class DocumentoFiscal
{
    public function __construct(
        private readonly string $tipo,   // RUC, DNI, NIT, RUT, CUIT, RFC, CE, PASAPORTE...
        private readonly string $numero,
    ) {
        if (trim($numero) === '') {
            throw new InvalidArgumentException('El documento fiscal no puede estar vacío.');
        }
    }

    public function tipo(): string
    {
        return $this->tipo;
    }

    public function numero(): string
    {
        return $this->numero;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\ValueObject\DocumentoFiscal;

/** Cliente que recibe el comprobante. */
final class Receptor
{
    public function __construct(
        private readonly DocumentoFiscal $documento,
        private readonly string $nombre,
        private readonly ?string $direccion = null,
        private readonly ?string $email = null,
    ) {}

    public function documento(): DocumentoFiscal { return $this->documento; }
    public function nombre(): string { return $this->nombre; }
    public function direccion(): ?string { return $this->direccion; }
    public function email(): ?string { return $this->email; }
}

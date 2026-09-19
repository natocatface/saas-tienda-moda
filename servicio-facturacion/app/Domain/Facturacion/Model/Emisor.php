<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\ValueObject\DocumentoFiscal;

/** Empresa que emite el comprobante (el tenant del SaaS). */
final class Emisor
{
    public function __construct(
        private readonly string $empresaId,
        private readonly DocumentoFiscal $documento,
        private readonly string $razonSocial,
        private readonly ?string $nombreComercial = null,
        private readonly ?string $direccion = null,
    ) {}

    public function empresaId(): string { return $this->empresaId; }
    public function documento(): DocumentoFiscal { return $this->documento; }
    public function razonSocial(): string { return $this->razonSocial; }
    public function nombreComercial(): ?string { return $this->nombreComercial; }
    public function direccion(): ?string { return $this->direccion; }
}

<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

/**
 * Nota de crédito: es un Comprobante que referencia a un comprobante afectado
 * y lleva un motivo. Reutiliza el agregado Comprobante por composición.
 */
final class NotaCredito
{
    public function __construct(
        private readonly Comprobante $comprobante,
        private readonly string $comprobanteAfectadoId,   // id del comprobante original
        private readonly string $serieAfectada,
        private readonly int $correlativoAfectado,
        private readonly string $motivoCodigo,             // catálogo por país (ej. SUNAT 01=anulación)
        private readonly string $motivoDescripcion,
    ) {}

    public function comprobante(): Comprobante { return $this->comprobante; }
    public function comprobanteAfectadoId(): string { return $this->comprobanteAfectadoId; }
    public function serieAfectada(): string { return $this->serieAfectada; }
    public function correlativoAfectado(): int { return $this->correlativoAfectado; }
    public function motivoCodigo(): string { return $this->motivoCodigo; }
    public function motivoDescripcion(): string { return $this->motivoDescripcion; }
}

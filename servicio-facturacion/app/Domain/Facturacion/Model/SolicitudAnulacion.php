<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/**
 * Solicitud de anulación / comunicación de baja / cancelación.
 * Cada país la implementa distinto (SUNAT: comunicación de baja o resumen;
 * SAT: cancelación con aceptación; ARCA/SII/DIAN: nota de crédito), pero el
 * contrato de entrada es común.
 */
final class SolicitudAnulacion
{
    public function __construct(
        private readonly IdentificadorComprobante $comprobanteId,
        private readonly string $motivo,
        private readonly ?string $codigoMotivo = null,   // catálogo específico de país (opcional)
    ) {}

    public function comprobanteId(): IdentificadorComprobante { return $this->comprobanteId; }
    public function motivo(): string { return $this->motivo; }
    public function codigoMotivo(): ?string { return $this->codigoMotivo; }
}

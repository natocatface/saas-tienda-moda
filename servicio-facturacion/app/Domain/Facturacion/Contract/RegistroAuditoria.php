<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Contract;

use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/**
 * Puerto de auditoría inmutable (append-only). Cada interacción con el
 * organismo y cada transición de estado se registra para trazabilidad legal.
 */
interface RegistroAuditoria
{
    /**
     * @param array<string,mixed> $contexto  payload de envío/respuesta, actor, ip, etc.
     */
    public function registrar(
        IdentificadorComprobante $comprobanteId,
        string $tipoEvento,
        ?EstadoComprobante $estadoAnterior,
        EstadoComprobante $estadoNuevo,
        array $contexto = [],
    ): void;
}

<?php

declare(strict_types=1);

namespace App\Application\Facturacion\DTO;

use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\Model\ResultadoOperacion;

/** Respuesta que el caso de uso devuelve al controlador (y este al ERP). */
final class ResultadoEmisionDTO
{
    public function __construct(
        public readonly string $comprobanteId,
        public readonly string $estado,
        public readonly ?string $idFiscal,
        public readonly ?string $mensaje,
    ) {}

    public static function desde(Comprobante $c, ?ResultadoOperacion $r = null): self
    {
        return new self(
            comprobanteId: $c->id()->valor(),
            estado: ($r?->estado() ?? $c->estado())->value,
            idFiscal: $r?->idFiscal() ?? $c->idFiscal(),
            mensaje: $r?->mensaje(),
        );
    }
}

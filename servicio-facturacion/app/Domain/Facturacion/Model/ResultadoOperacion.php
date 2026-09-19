<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\ValueObject\EstadoComprobante;

/**
 * Resultado normalizado de cualquier operación fiscal, para cualquier país.
 *
 * Los detalles propios del organismo (CDR de SUNAT, CAE de ARCA, UUID del SAT,
 * CUFE de DIAN, XML de respuesta...) NO se exponen como campos con nombre: van
 * en $datosProveedor para no filtrar la especificidad de país al resto del
 * sistema. Los campos con nombre (estado, codigo, mensaje, idFiscal) son
 * comunes y estables.
 */
final class ResultadoOperacion
{
    /**
     * @param array<string,mixed> $datosProveedor  p. ej. ['cdr' => '...', 'cae' => '...', 'ticket' => '...']
     */
    public function __construct(
        private readonly EstadoComprobante $estado,
        private readonly ?string $idFiscal = null,   // CDR/CAE/UUID/CUFE cuando aplica
        private readonly ?string $codigo = null,      // código normalizado del SFE
        private readonly ?string $mensaje = null,
        private readonly array $datosProveedor = [],
    ) {}

    public function estado(): EstadoComprobante { return $this->estado; }
    public function idFiscal(): ?string { return $this->idFiscal; }
    public function codigo(): ?string { return $this->codigo; }
    public function mensaje(): ?string { return $this->mensaje; }

    /** @return array<string,mixed> */
    public function datosProveedor(): array { return $this->datosProveedor; }

    public function fueAceptado(): bool
    {
        return $this->estado === EstadoComprobante::ACEPTADO
            || $this->estado === EstadoComprobante::OBSERVADO;
    }
}

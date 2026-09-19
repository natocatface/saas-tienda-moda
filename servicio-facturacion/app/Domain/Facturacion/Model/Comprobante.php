<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Model;

use App\Domain\Facturacion\Exception\ComprobanteInvalidoException;
use App\Domain\Facturacion\ValueObject\Dinero;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use App\Domain\Facturacion\ValueObject\Moneda;
use App\Domain\Facturacion\ValueObject\Pais;
use App\Domain\Facturacion\ValueObject\TipoComprobante;

/**
 * Agregado raíz. Encapsula las invariantes de un comprobante tributario que
 * son COMUNES a todos los países. Las reglas específicas de cada organismo
 * viven en su adaptador, no aquí.
 */
final class Comprobante
{
    /** @var LineaComprobante[] */
    private array $lineas = [];

    private EstadoComprobante $estado = EstadoComprobante::RECIBIDO;
    private ?string $idFiscal = null;   // CDR / CAE / UUID / CUFE devuelto por el organismo

    public function __construct(
        private readonly IdentificadorComprobante $id,
        private readonly Pais $pais,
        private readonly TipoComprobante $tipo,
        private readonly string $serie,
        private readonly int $correlativo,
        private readonly Moneda $moneda,
        private readonly Emisor $emisor,
        private readonly Receptor $receptor,
        private readonly string $referenciaExterna,   // id de la venta en el ERP (idempotencia)
    ) {}

    public function agregarLinea(LineaComprobante $linea): void
    {
        $this->lineas[] = $linea;
    }

    /** Valida invariantes universales antes de enviar al organismo. */
    public function validar(): void
    {
        if ($this->lineas === []) {
            throw new ComprobanteInvalidoException('El comprobante debe tener al menos una línea.');
        }
        if ($this->correlativo <= 0) {
            throw new ComprobanteInvalidoException('El correlativo debe ser positivo.');
        }
    }

    public function totalGravado(): Dinero
    {
        return array_reduce(
            $this->lineas,
            fn (Dinero $acc, LineaComprobante $l) => $acc->sumar($l->baseGravable()),
            Dinero::cero($this->moneda),
        );
    }

    public function totalImpuestos(): Dinero
    {
        return array_reduce(
            $this->lineas,
            fn (Dinero $acc, LineaComprobante $l) => $acc->sumar($l->impuesto()),
            Dinero::cero($this->moneda),
        );
    }

    public function total(): Dinero
    {
        return $this->totalGravado()->sumar($this->totalImpuestos());
    }

    public function marcarAceptado(string $idFiscal): void
    {
        $this->estado = EstadoComprobante::ACEPTADO;
        $this->idFiscal = $idFiscal;
    }

    public function marcarEstado(EstadoComprobante $estado): void
    {
        $this->estado = $estado;
    }

    // --- getters ---
    public function id(): IdentificadorComprobante { return $this->id; }
    public function pais(): Pais { return $this->pais; }
    public function tipo(): TipoComprobante { return $this->tipo; }
    public function serie(): string { return $this->serie; }
    public function correlativo(): int { return $this->correlativo; }
    public function moneda(): Moneda { return $this->moneda; }
    public function emisor(): Emisor { return $this->emisor; }
    public function receptor(): Receptor { return $this->receptor; }
    public function referenciaExterna(): string { return $this->referenciaExterna; }
    public function estado(): EstadoComprobante { return $this->estado; }
    public function idFiscal(): ?string { return $this->idFiscal; }

    /** @return LineaComprobante[] */
    public function lineas(): array { return $this->lineas; }
}

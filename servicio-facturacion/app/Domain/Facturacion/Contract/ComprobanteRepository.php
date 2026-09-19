<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Contract;

use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/** Puerto de persistencia del agregado Comprobante (lo implementa Infrastructure). */
interface ComprobanteRepository
{
    public function guardar(Comprobante $comprobante): void;

    public function porId(IdentificadorComprobante $id): ?Comprobante;

    /** Idempotencia: recupera un comprobante por la referencia de la venta del ERP. */
    public function porReferenciaExterna(string $empresaId, string $referenciaExterna): ?Comprobante;

    /** Siguiente correlativo para una serie (numeración fiscal sin huecos). */
    public function siguienteCorrelativo(string $empresaId, string $pais, string $serie): int;
}

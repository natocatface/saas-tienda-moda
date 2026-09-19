<?php

declare(strict_types=1);

namespace App\Application\Facturacion\Contract;

use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\ValueObject\Pais;

/** Selecciona el adaptador de país correcto en tiempo de ejecución. */
interface FabricaProveedor
{
    public function para(Pais $pais): ProveedorFacturacion;
}

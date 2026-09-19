<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Exception;

use App\Domain\Facturacion\ValueObject\Pais;

final class ProveedorNoSoportadoException extends FacturacionException
{
    public function __construct(Pais $pais)
    {
        parent::__construct(
            "No hay proveedor de facturación registrado para el país {$pais->codigoIso()}.",
            self::CAT_CONFIGURACION,
            'PROVEEDOR_NO_SOPORTADO',
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Exception;

final class ComprobanteInvalidoException extends FacturacionException
{
    public function __construct(string $mensaje)
    {
        parent::__construct($mensaje, self::CAT_VALIDACION, 'COMPROBANTE_INVALIDO');
    }
}

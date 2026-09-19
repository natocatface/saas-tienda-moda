<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Exception;

/** El organismo (SUNAT, DIAN, SAT...) rechazó el comprobante por motivos fiscales. */
final class RechazoOrganismoException extends FacturacionException
{
    public function __construct(string $mensaje, string $codigoOrganismo, array $detalle = [])
    {
        parent::__construct($mensaje, self::CAT_NEGOCIO, "ORG_{$codigoOrganismo}", $detalle);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Domain\Facturacion\Contract\ProveedorFacturacion;
use App\Domain\Facturacion\Exception\ProveedorNoSoportadoException;
use App\Domain\Facturacion\ValueObject\Pais;
use Psr\Container\ContainerInterface;

/**
 * Resuelve el adaptador de país en tiempo de ejecución a partir del mapa
 * definido en config/facturacion.php. Es el ÚNICO punto que se amplía al
 * agregar un país nuevo (una línea en el config). No contiene lógica de país.
 */
final class FabricaProveedorFacturacion implements FabricaProveedor
{
    /** @param array<string,class-string<ProveedorFacturacion>> $mapa  código ISO => clase adaptador */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly array $mapa,
    ) {}

    public function para(Pais $pais): ProveedorFacturacion
    {
        $clase = $this->mapa[$pais->codigoIso()] ?? null;
        if ($clase === null) {
            throw new ProveedorNoSoportadoException($pais);
        }

        /** @var ProveedorFacturacion $proveedor */
        $proveedor = $this->container->get($clase);   // el contenedor resuelve colaboradores (DI)

        if (!$proveedor->soporta($pais)) {
            throw new ProveedorNoSoportadoException($pais);
        }

        return $proveedor;
    }
}

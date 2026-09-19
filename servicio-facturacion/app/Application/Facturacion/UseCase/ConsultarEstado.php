<?php

declare(strict_types=1);

namespace App\Application\Facturacion\UseCase;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Application\Facturacion\DTO\ResultadoEmisionDTO;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Exception\ComprobanteInvalidoException;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/** Caso de uso: consultar el estado de un comprobante ante el organismo. */
final class ConsultarEstado
{
    public function __construct(
        private readonly ComprobanteRepository $repositorio,
        private readonly FabricaProveedor $fabrica,
    ) {}

    public function ejecutar(string $comprobanteId): ResultadoEmisionDTO
    {
        $id = IdentificadorComprobante::desde($comprobanteId);
        $comprobante = $this->repositorio->porId($id);
        if ($comprobante === null) {
            throw new ComprobanteInvalidoException("Comprobante no encontrado: {$comprobanteId}");
        }

        // Si ya está en un estado final, no hace falta preguntar al organismo.
        if ($comprobante->estado()->esFinal()) {
            return ResultadoEmisionDTO::desde($comprobante);
        }

        $proveedor = $this->fabrica->para($comprobante->pais());
        $resultado = $proveedor->consultarEstado($id);
        $comprobante->marcarEstado($resultado->estado());
        $this->repositorio->guardar($comprobante);

        return ResultadoEmisionDTO::desde($comprobante, $resultado);
    }
}

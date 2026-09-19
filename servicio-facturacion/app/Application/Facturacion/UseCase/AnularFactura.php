<?php

declare(strict_types=1);

namespace App\Application\Facturacion\UseCase;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Application\Facturacion\DTO\ResultadoEmisionDTO;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Domain\Facturacion\Exception\ComprobanteInvalidoException;
use App\Domain\Facturacion\Model\SolicitudAnulacion;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/** Caso de uso: anular / dar de baja / cancelar un comprobante. */
final class AnularFactura
{
    public function __construct(
        private readonly ComprobanteRepository $repositorio,
        private readonly FabricaProveedor $fabrica,
        private readonly RegistroAuditoria $auditoria,
    ) {}

    public function ejecutar(string $comprobanteId, string $motivo, ?string $codigoMotivo = null): ResultadoEmisionDTO
    {
        $id = IdentificadorComprobante::desde($comprobanteId);
        $comprobante = $this->repositorio->porId($id);
        if ($comprobante === null) {
            throw new ComprobanteInvalidoException("Comprobante no encontrado: {$comprobanteId}");
        }

        $estadoAnterior = $comprobante->estado();
        $proveedor = $this->fabrica->para($comprobante->pais());
        $resultado = $proveedor->anularFactura(new SolicitudAnulacion($id, $motivo, $codigoMotivo));

        $comprobante->marcarEstado($resultado->estado());
        $this->repositorio->guardar($comprobante);
        $this->auditoria->registrar($id, 'ANULACION', $estadoAnterior, $resultado->estado(), ['motivo' => $motivo]);

        return ResultadoEmisionDTO::desde($comprobante, $resultado);
    }
}

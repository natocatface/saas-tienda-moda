<?php

declare(strict_types=1);

namespace App\Application\Facturacion\UseCase;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Application\Facturacion\DTO\ResultadoEmisionDTO;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Domain\Facturacion\Exception\ComprobanteInvalidoException;
use App\Domain\Facturacion\Model\NotaCredito;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/**
 * Caso de uso: emitir una nota de crédito sobre un comprobante existente.
 * Recibe una NotaCredito ya construida (el mapeo desde el request lo hace un
 * ensamblador en la capa Http para no engordar el caso de uso).
 */
final class EmitirNotaCredito
{
    public function __construct(
        private readonly ComprobanteRepository $repositorio,
        private readonly FabricaProveedor $fabrica,
        private readonly RegistroAuditoria $auditoria,
    ) {}

    public function ejecutar(NotaCredito $notaCredito): ResultadoEmisionDTO
    {
        $afectadoId = IdentificadorComprobante::desde($notaCredito->comprobanteAfectadoId());
        if ($this->repositorio->porId($afectadoId) === null) {
            throw new ComprobanteInvalidoException('El comprobante afectado no existe.');
        }

        $nc = $notaCredito->comprobante();
        $nc->validar();
        $this->repositorio->guardar($nc);

        $proveedor = $this->fabrica->para($nc->pais());
        $resultado = $proveedor->emitirNotaCredito($notaCredito);

        $nc->marcarEstado($resultado->estado());
        if ($resultado->fueAceptado() && $resultado->idFiscal() !== null) {
            $nc->marcarAceptado($resultado->idFiscal());
        }
        $this->repositorio->guardar($nc);
        $this->auditoria->registrar($nc->id(), 'NOTA_CREDITO', EstadoComprobante::RECIBIDO, $resultado->estado(), []);

        return ResultadoEmisionDTO::desde($nc, $resultado);
    }
}

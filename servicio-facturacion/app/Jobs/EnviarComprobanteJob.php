<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Application\Facturacion\Contract\FabricaProveedor;
use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Envía el comprobante al organismo de forma ASÍNCRONA con reintentos.
 *
 * - Solo reintenta ante errores de categoría COMUNICACION (técnicos).
 * - Backoff exponencial (config facturacion.reintentos.backoff).
 * - Antes de reenviar, consulta el estado para no duplicar (idempotencia).
 */
final class EnviarComprobanteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(public readonly string $comprobanteId) {}

    /** Backoff por intento (segundos). */
    public function backoff(): array
    {
        return config('facturacion.reintentos.backoff', [0, 30, 120, 600, 3600]);
    }

    public function handle(
        ComprobanteRepository $repositorio,
        FabricaProveedor $fabrica,
        RegistroAuditoria $auditoria,
    ): void {
        $id = IdentificadorComprobante::desde($this->comprobanteId);
        $comprobante = $repositorio->porId($id);
        if ($comprobante === null || $comprobante->estado()->esFinal()) {
            return; // nada que hacer
        }

        $proveedor = $fabrica->para($comprobante->pais());

        try {
            $resultado = $proveedor->emitirFactura($comprobante);
        } catch (FacturacionException $e) {
            if ($e->esReintentable()) {
                throw $e; // la cola lo reintenta con backoff
            }
            // Rechazo fiscal: estado ERROR/RECHAZADO persistente, sin reintento.
            $comprobante->marcarEstado(\App\Domain\Facturacion\ValueObject\EstadoComprobante::RECHAZADO);
            $repositorio->guardar($comprobante);
            $auditoria->registrar($id, 'RECHAZO', null, $comprobante->estado(), ['error' => $e->getMessage()]);
            return;
        }

        $comprobante->marcarEstado($resultado->estado());
        if ($resultado->fueAceptado() && $resultado->idFiscal() !== null) {
            $comprobante->marcarAceptado($resultado->idFiscal());
        }
        $repositorio->guardar($comprobante);
        // TODO: disparar webhook al ERP con el estado final.
    }
}

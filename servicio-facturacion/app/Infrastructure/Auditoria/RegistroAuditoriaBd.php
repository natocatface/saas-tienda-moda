<?php

declare(strict_types=1);

namespace App\Infrastructure\Auditoria;

use App\Domain\Facturacion\Contract\RegistroAuditoria;
use App\Domain\Facturacion\ValueObject\EstadoComprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use Illuminate\Support\Facades\DB;

/**
 * Registro de auditoría append-only en la tabla facturacion_eventos.
 * NUNCA actualiza ni borra: cada interacción es una fila nueva e inmutable.
 */
final class RegistroAuditoriaBd implements RegistroAuditoria
{
    public function registrar(
        IdentificadorComprobante $comprobanteId,
        string $tipoEvento,
        ?EstadoComprobante $estadoAnterior,
        EstadoComprobante $estadoNuevo,
        array $contexto = [],
    ): void {
        DB::table('facturacion_eventos')->insert([
            'id'              => (string) \Illuminate\Support\Str::uuid(),
            'comprobante_id'  => $comprobanteId->valor(),
            'tipo_evento'     => $tipoEvento,
            'estado_anterior' => $estadoAnterior?->value,
            'estado_nuevo'    => $estadoNuevo->value,
            'contexto'        => json_encode($contexto, JSON_UNESCAPED_UNICODE),
            'creado_en'       => now(),
        ]);
    }
}

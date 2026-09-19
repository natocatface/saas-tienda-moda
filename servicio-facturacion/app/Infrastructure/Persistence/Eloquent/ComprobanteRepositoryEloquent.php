<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Facturacion\Contract\ComprobanteRepository;
use App\Domain\Facturacion\Model\Comprobante;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use Illuminate\Support\Facades\DB;

/**
 * Implementación del puerto de persistencia con la BD del SFE.
 *
 * El agregado de dominio (Comprobante) es rico y no es una fila de tabla; por
 * eso se usa un MAPEADOR entre el dominio y las columnas. Aquí se muestra la
 * escritura de la cabecera y el cálculo de correlativo; la hidratación completa
 * (reconstruir el agregado con sus líneas) se implementa en `porId()`.
 */
final class ComprobanteRepositoryEloquent implements ComprobanteRepository
{
    public function guardar(Comprobante $c): void
    {
        DB::table('comprobantes')->updateOrInsert(
            ['id' => $c->id()->valor()],
            [
                'empresa_id'         => $c->emisor()->empresaId(),
                'pais'               => $c->pais()->codigoIso(),
                'tipo'               => $c->tipo()->value,
                'serie'              => $c->serie(),
                'correlativo'        => $c->correlativo(),
                'moneda'             => $c->moneda()->codigo(),
                'total'              => $c->total()->monto(),
                'estado'             => $c->estado()->value,
                'id_fiscal'          => $c->idFiscal(),
                'referencia_externa' => $c->referenciaExterna(),
                'actualizado_en'     => now(),
            ],
        );
        // TODO: persistir líneas en comprobante_lineas (borrar+insertar o diff).
    }

    public function porId(IdentificadorComprobante $id): ?Comprobante
    {
        $fila = DB::table('comprobantes')->where('id', $id->valor())->first();
        if ($fila === null) {
            return null;
        }
        // TODO: rehidratar el agregado Comprobante con sus líneas mediante un
        //       ComprobanteMapper::desdeFila($fila, $lineas). Se omite aquí por
        //       brevedad del scaffold.
        return null;
    }

    public function porReferenciaExterna(string $empresaId, string $referenciaExterna): ?Comprobante
    {
        $existe = DB::table('comprobantes')
            ->where('empresa_id', $empresaId)
            ->where('referencia_externa', $referenciaExterna)
            ->exists();

        // TODO: si existe, rehidratar y devolver (idempotencia). Ver porId().
        return $existe ? $this->porId(IdentificadorComprobante::desde('')) : null;
    }

    public function siguienteCorrelativo(string $empresaId, string $pais, string $serie): int
    {
        // Numeración fiscal sin huecos: se recomienda tabla de contadores con
        // bloqueo (SELECT ... FOR UPDATE) dentro de una transacción.
        $max = DB::table('comprobantes')
            ->where('empresa_id', $empresaId)
            ->where('pais', $pais)
            ->where('serie', $serie)
            ->max('correlativo');

        return ((int) $max) + 1;
    }
}

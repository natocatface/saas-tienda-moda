<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Application\Facturacion\DTO\EmitirFacturaCommand;
use App\Application\Facturacion\UseCase\ConsultarEstado;
use App\Application\Facturacion\UseCase\EmitirFactura;
use App\Domain\Facturacion\Exception\FacturacionException;
use App\Http\Requests\EmitirFacturaRequest;
use Illuminate\Http\JsonResponse;

/**
 * API REST v1 — Facturas. Traduce HTTP <-> casos de uso. No contiene lógica de
 * negocio ni de país: solo mapea request -> Command y Result -> JSON.
 */
final class FacturaController
{
    public function emitir(EmitirFacturaRequest $request, EmitirFactura $useCase): JsonResponse
    {
        $datos = $request->validated();

        $command = new EmitirFacturaCommand(
            pais: $datos['pais'],
            tipo: $datos['tipo'],
            moneda: $datos['moneda'],
            empresaId: $request->attributes->get('empresa_id', $datos['empresa_id'] ?? ''),
            emisorDocumento: $datos['emisor']['documento'],
            emisorRazonSocial: $datos['emisor']['razon_social'],
            receptorTipoDoc: $datos['receptor']['tipo_doc'],
            receptorDocumento: $datos['receptor']['documento'],
            receptorNombre: $datos['receptor']['nombre'],
            referenciaExterna: $datos['referencia_externa'],
            lineas: $datos['lineas'],
            serie: $datos['serie'] ?? null,
        );

        try {
            $resultado = $useCase->ejecutar($command);
        } catch (FacturacionException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'codigo' => $e->codigoSfe(),
                'categoria' => $e->categoria(),
            ], $e->categoria() === FacturacionException::CAT_VALIDACION ? 422 : 409);
        }

        return response()->json([
            'id' => $resultado->comprobanteId,
            'estado' => $resultado->estado,
            'id_fiscal' => $resultado->idFiscal,
            'mensaje' => $resultado->mensaje,
            'consultar_en' => "/api/v1/comprobantes/{$resultado->comprobanteId}",
        ], 202);
    }

    public function estado(string $id, ConsultarEstado $useCase): JsonResponse
    {
        $r = $useCase->ejecutar($id);
        return response()->json([
            'id' => $r->comprobanteId,
            'estado' => $r->estado,
            'id_fiscal' => $r->idFiscal,
        ]);
    }
}

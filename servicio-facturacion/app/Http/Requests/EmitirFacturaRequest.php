<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Validación de entrada (categoría VALIDACION del §10). Reglas UNIVERSALES. */
final class EmitirFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autenticación de la empresa se hace en middleware
    }

    public function rules(): array
    {
        return [
            'pais'                 => ['required', 'string', 'size:2'],
            'tipo'                 => ['required', 'in:FACTURA,BOLETA,NOTA_CREDITO,NOTA_DEBITO'],
            'moneda'               => ['required', 'string', 'size:3'],
            'referencia_externa'   => ['required', 'string', 'max:100'],
            'emisor.documento'     => ['required', 'string'],
            'emisor.razon_social'  => ['required', 'string'],
            'receptor.tipo_doc'    => ['required', 'string'],
            'receptor.documento'   => ['required', 'string'],
            'receptor.nombre'      => ['required', 'string'],
            'lineas'               => ['required', 'array', 'min:1'],
            'lineas.*.descripcion' => ['required', 'string'],
            'lineas.*.cantidad'    => ['required', 'numeric', 'gt:0'],
            'lineas.*.precio_unitario' => ['required', 'numeric', 'gte:0'],
            'lineas.*.tasa_impuesto'   => ['nullable', 'numeric', 'gte:0'],
            'serie'                => ['nullable', 'string', 'max:10'],
        ];
    }
}

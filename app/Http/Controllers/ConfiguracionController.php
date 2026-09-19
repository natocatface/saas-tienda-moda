<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);
        return view('configuracion.index', compact('tienda'));
    }

    public function update(Request $request)
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);

        $data = $request->validate([
            'igv'            => 'required|numeric|min:0|max:100',
            'moneda'         => 'required|string|max:10',
            'simbolo_moneda' => 'required|string|max:5',
            'serie_boleta'   => 'required|string|max:10',
            'serie_factura'  => 'required|string|max:10',
        ]);

        $tienda->update($data);
        return back()->with('success', 'Configuración guardada correctamente.');
    }
}

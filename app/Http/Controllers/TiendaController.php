<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function edit()
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);
        $tienda->load('plan');
        return view('tienda.edit', compact('tienda'));
    }

    public function update(Request $request)
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);

        $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'ruc'       => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
        ]);

        $tienda->update($data);
        return back()->with('success', 'Los datos de tu tienda se actualizaron.');
    }
}

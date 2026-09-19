<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('ruc', 'like', '%' . $request->buscar . '%');
        }
        $proveedores = $query->orderBy('nombre')->paginate(15);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create() { return view('proveedores.create'); }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:200']);
        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado.');
    }

    public function edit(Proveedor $proveedor) { return view('proveedores.edit', compact('proveedor')); }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate(['nombre' => 'required|string|max:200']);
        $proveedor->update($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->update(['activo' => false]);
        return back()->with('success', 'Proveedor desactivado.');
    }
}

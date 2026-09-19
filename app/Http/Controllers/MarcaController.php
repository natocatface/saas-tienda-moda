<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index(Request $request)
    {
        $query = Marca::withCount('productos');
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        $marcas = $query->orderBy('nombre')->get();
        $editar = $request->filled('edit') ? Marca::find($request->edit) : null;
        return view('marcas.index', compact('marcas', 'editar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:100']);
        $data['activo'] = $request->boolean('activo', true);
        Marca::create($data);
        return redirect()->route('marcas.index')->with('success', 'Marca creada exitosamente.');
    }

    public function update(Request $request, Marca $marca)
    {
        $data = $request->validate(['nombre' => 'required|string|max:100']);
        $data['activo'] = $request->boolean('activo', true);
        $marca->update($data);
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada.');
    }

    public function destroy(Marca $marca)
    {
        if ($marca->productos()->count() > 0) {
            $marca->update(['activo' => false]);
            return back()->with('success', 'La marca tiene productos; se desactivó en lugar de eliminarse.');
        }
        $marca->delete();
        return back()->with('success', 'Marca eliminada.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::withCount('productos');
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        $categorias = $query->orderBy('nombre')->get();
        $editar = $request->filled('edit') ? Categoria::find($request->edit) : null;
        return view('categorias.index', compact('categorias', 'editar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'genero'      => 'required|in:damas,caballeros,ninos,unisex',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $data['slug']   = $this->slugUnico($data['nombre']);
        $data['activo'] = $request->boolean('activo', true);
        Categoria::create($data);
        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'genero'      => 'required|in:damas,caballeros,ninos,unisex',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $data['slug']   = $this->slugUnico($data['nombre'], $categoria->id);
        $data['activo'] = $request->boolean('activo', true);
        $categoria->update($data);
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            $categoria->update(['activo' => false]);
            return back()->with('success', 'La categoría tiene productos; se desactivó en lugar de eliminarse.');
        }
        $categoria->delete();
        return back()->with('success', 'Categoría eliminada.');
    }

    private function slugUnico(string $nombre, ?int $ignorar = null): string
    {
        $base = Str::slug($nombre); $slug = $base; $i = 1;
        // Se ignoran los scopes globales (multi-tienda) para que el slug sea único
        // a nivel de plataforma, igual que el índice UNIQUE de la columna "slug".
        while (Categoria::withoutGlobalScopes()->where('slug', $slug)->when($ignorar, fn ($q) => $q->where('id', '!=', $ignorar))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

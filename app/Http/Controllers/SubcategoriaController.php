<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcategoria::with('categoria')->withCount('productos');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $subcategorias = $query->orderBy('nombre')->get();
        $categorias    = Categoria::where('activo', true)->orderBy('nombre')->get();
        $editar        = $request->filled('edit') ? Subcategoria::find($request->edit) : null;

        return view('subcategorias.index', compact('subcategorias', 'categorias', 'editar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre'       => 'required|string|max:100',
        ]);
        $data['slug']   = $this->slugUnico($data['nombre']);
        $data['activo'] = $request->boolean('activo', true);

        Subcategoria::create($data);

        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría creada exitosamente.');
    }

    public function update(Request $request, Subcategoria $subcategoria)
    {
        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre'       => 'required|string|max:100',
        ]);
        $data['slug']   = $this->slugUnico($data['nombre'], $subcategoria->id);
        $data['activo'] = $request->boolean('activo', true);

        $subcategoria->update($data);

        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría actualizada.');
    }

    public function destroy(Subcategoria $subcategoria)
    {
        if ($subcategoria->productos()->count() > 0) {
            $subcategoria->update(['activo' => false]);
            return back()->with('success', 'La subcategoría tiene productos; se desactivó en lugar de eliminarse.');
        }
        $subcategoria->delete();
        return back()->with('success', 'Subcategoría eliminada.');
    }

    private function slugUnico(string $nombre, ?int $ignorar = null): string
    {
        $base = Str::slug($nombre); $slug = $base; $i = 1;
        // Slug único a nivel de plataforma (el índice UNIQUE es global).
        while (Subcategoria::withoutGlobalScopes()->where('slug', $slug)->when($ignorar, fn ($q) => $q->where('id', '!=', $ignorar))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

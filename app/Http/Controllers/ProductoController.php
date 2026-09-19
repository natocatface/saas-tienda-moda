<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\ProductoVariante;
use App\Models\DetalleVenta;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'marca']);

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
            });
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $productos   = $query->orderByDesc('created_at')->paginate(15);
        $categorias  = Categoria::where('activo', true)->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $marcas     = Marca::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        $subcategorias = \App\Models\Subcategoria::where('activo', true)->get(['id', 'nombre', 'categoria_id']);
        return view('productos.create', compact('categorias', 'marcas', 'proveedores', 'subcategorias'));
    }

    public function store(Request $request)
    {
        // Límite del plan de la tienda
        $tienda = auth()->user()->tienda;
        if ($tienda && !$tienda->puedeAgregar('productos')) {
            return back()->withInput()->with('error',
                'Has alcanzado el límite de productos de tu plan ' . ($tienda->plan->nombre ?? '') .
                ' (' . ($tienda->plan->max_productos ?? 0) . '). Mejora tu plan para agregar más.');
        }

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_venta' => 'required|numeric|min:0',
            'genero'       => 'required|in:damas,caballeros,ninos,unisex',
            'tipo'         => 'required|in:casual,deportivo,formal,otro',
        ]);

        $data = $request->all();
        $data['codigo'] = 'PROD-' . strtoupper(Str::random(6));

        // Claves foráneas opcionales: vacío -> null (evita errores de integridad)
        foreach (['subcategoria_id', 'marca_id', 'proveedor_id'] as $fk) {
            if (empty($data[$fk])) {
                $data[$fk] = null;
            }
        }

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        // Guardar variantes si existen
        if ($request->has('variantes')) {
            foreach ($request->variantes as $v) {
                if (!empty($v['talla']) && !empty($v['color'])) {
                    ProductoVariante::create([
                        'producto_id'  => $producto->id,
                        'talla'        => $v['talla'],
                        'color'        => $v['color'],
                        'stock'        => $v['stock'] ?? 0,
                        'stock_minimo' => $v['stock_minimo'] ?? 5,
                    ]);
                }
            }
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias  = Categoria::where('activo', true)->get();
        $marcas      = Marca::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        $subcategorias = \App\Models\Subcategoria::where('activo', true)->get(['id', 'nombre', 'categoria_id']);
        $producto->load('variantes');
        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores', 'subcategorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_venta' => 'required|numeric|min:0',
        ]);

        $data = $request->except(['imagen', 'variantes_existentes', 'variantes_nuevas', 'variantes_eliminar', '_token', '_method']);
        $data['activo']    = $request->boolean('activo');
        $data['destacado'] = $request->boolean('destacado');

        // Claves foráneas opcionales: vacío -> null
        foreach (['subcategoria_id', 'marca_id', 'proveedor_id'] as $fk) {
            if (empty($data[$fk])) {
                $data[$fk] = null;
            }
        }
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        // IDs marcados para eliminar
        $eliminar = array_map('intval', (array) $request->input('variantes_eliminar', []));

        // Actualizar variantes existentes (saltando las marcadas para eliminar)
        foreach ((array) $request->input('variantes_existentes', []) as $id => $v) {
            if (in_array((int) $id, $eliminar, true)) {
                continue;
            }
            $variante = $producto->variantes()->find($id);
            if (!$variante || empty($v['talla']) || empty($v['color'])) {
                continue;
            }
            $variante->update([
                'talla'        => $v['talla'],
                'color'        => $v['color'],
                'stock'        => (int) ($v['stock'] ?? 0),
                'stock_minimo' => (int) ($v['stock_minimo'] ?? 5),
            ]);
        }

        // Eliminar variantes marcadas (solo si no tienen ventas/compras asociadas)
        $protegidas = 0;
        foreach ($eliminar as $id) {
            $variante = $producto->variantes()->find($id);
            if (!$variante) {
                continue;
            }
            $referenciada = DetalleVenta::where('variante_id', $id)->exists()
                || DetalleCompra::where('variante_id', $id)->exists();
            if ($referenciada) {
                $protegidas++;
                continue;
            }
            $variante->delete();
        }

        // Crear variantes nuevas
        foreach ((array) $request->input('variantes_nuevas', []) as $v) {
            if (!empty($v['talla']) && !empty($v['color'])) {
                ProductoVariante::create([
                    'producto_id'  => $producto->id,
                    'talla'        => $v['talla'],
                    'color'        => $v['color'],
                    'stock'        => (int) ($v['stock'] ?? 0),
                    'stock_minimo' => (int) ($v['stock_minimo'] ?? 5),
                ]);
            }
        }

        $msg = 'Producto actualizado.';
        if ($protegidas > 0) {
            $msg .= " ({$protegidas} variante(s) no se eliminaron por tener ventas o compras asociadas).";
        }

        return redirect()->route('productos.index')->with('success', $msg);
    }

    public function destroy(Producto $producto)
    {
        $producto->update(['activo' => false]);
        return back()->with('success', 'Producto desactivado.');
    }

    public function show(Producto $producto)
    {
        $producto->load('variantes', 'categoria', 'marca', 'proveedor');
        return view('productos.show', compact('producto'));
    }
}

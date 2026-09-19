<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::with('proveedor');
        if ($request->filled('buscar')) {
            $query->where('numero_compra', 'like', '%' . $request->buscar . '%');
        }
        $compras = $query->orderByDesc('fecha')->orderByDesc('id')->paginate(15);
        $totalMes = Compra::where('estado', '!=', 'anulado')
            ->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('total');
        return view('compras.index', compact('compras', 'totalMes'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $variantes   = ProductoVariante::with('producto')->get()->map(function ($v) {
            return [
                'id'      => $v->id,
                'texto'   => ($v->producto->nombre ?? 'Producto') . ' — ' . $v->talla . '/' . $v->color . ' (stock: ' . $v->stock . ')',
                'precio'  => (float) ($v->producto->precio_compra ?? 0),
            ];
        });
        return view('compras.create', compact('proveedores', 'variantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id'        => 'required|exists:proveedores,id',
            'fecha'               => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.variante_id' => 'nullable|exists:producto_variantes,id',
            'items.*.cantidad'    => 'nullable|integer|min:1',
            'items.*.precio'      => 'nullable|numeric|min:0',
        ]);

        $items = collect($request->items)->filter(fn ($it) => !empty($it['variante_id']) && !empty($it['cantidad']));
        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Agrega al menos un producto a la compra.');
        }

        DB::transaction(function () use ($request, $items) {
            $tid = auth()->user()->tienda_id;
            $compra = Compra::create([
                'numero_compra' => 'C-' . $tid . '-' . date('Y') . '-' . str_pad(Compra::count() + 1, 5, '0', STR_PAD_LEFT),
                'proveedor_id'  => $request->proveedor_id,
                'user_id'       => auth()->id(),
                'fecha'         => $request->fecha,
                'estado'        => 'recibido',
                'observaciones' => $request->observaciones,
                'total'         => 0,
            ]);

            $total = 0;
            foreach ($items as $it) {
                $variante = ProductoVariante::find($it['variante_id']);
                if (!$variante) continue;
                $cantidad = (int) $it['cantidad'];
                $precio   = (float) ($it['precio'] ?? $variante->producto->precio_compra ?? 0);
                $sub      = round($precio * $cantidad, 2);
                $total   += $sub;

                DetalleCompra::create([
                    'compra_id'       => $compra->id,
                    'producto_id'     => $variante->producto_id,
                    'variante_id'     => $variante->id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'        => $sub,
                ]);

                // Suma el stock al inventario
                $variante->increment('stock', $cantidad);
            }
            $compra->update(['total' => round($total, 2)]);
        });

        return redirect()->route('compras.index')->with('success', 'Compra registrada y stock actualizado.');
    }

    public function show(Compra $compra)
    {
        $compra->load('proveedor', 'usuario', 'detalles.producto', 'detalles.variante');
        return view('compras.show', compact('compra'));
    }

    /** Anula la compra y revierte (descuenta) el stock que había ingresado. */
    public function anular(Compra $compra)
    {
        if ($compra->estado === 'anulado') {
            return back()->with('error', 'Esta compra ya está anulada.');
        }

        DB::transaction(function () use ($compra) {
            foreach ($compra->detalles as $d) {
                if (!$d->variante_id) {
                    continue;
                }
                $variante = ProductoVariante::lockForUpdate()->find($d->variante_id);
                if ($variante) {
                    // No permitir stock negativo si ya se vendieron unidades.
                    $nuevo = max(0, $variante->stock - $d->cantidad);
                    $variante->update(['stock' => $nuevo]);
                }
            }
            $compra->update(['estado' => 'anulado']);
        });

        return back()->with('success', 'Compra anulada y stock revertido.');
    }
}

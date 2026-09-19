<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductoVariante::with('producto.categoria');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('color', 'like', "%$b%")
                  ->orWhere('talla', 'like', "%$b%")
                  ->orWhereHas('producto', fn ($p) => $p->where('nombre', 'like', "%$b%")->orWhere('codigo', 'like', "%$b%"));
            });
        }
        if ($request->filled('filtro') && $request->filtro === 'bajo') {
            $query->whereColumn('stock', '<=', 'stock_minimo');
        }

        $variantes = $query->orderBy('stock')->paginate(20);

        // Resumen
        $totalUnidades = ProductoVariante::sum('stock');
        $bajoStock     = ProductoVariante::whereColumn('stock', '<=', 'stock_minimo')->count();
        $sinStock      = ProductoVariante::where('stock', 0)->count();

        return view('stock.index', compact('variantes', 'totalUnidades', 'bajoStock', 'sinStock'));
    }

    public function update(Request $request, ProductoVariante $variante)
    {
        $data = $request->validate([
            'stock'        => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);
        $variante->update($data);
        return back()->with('success', 'Stock actualizado para ' . $variante->producto->nombre . '.');
    }
}

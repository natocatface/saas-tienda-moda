<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'vendedor']);
        if ($request->filled('desde')) $query->where('fecha', '>=', $request->desde);
        if ($request->filled('hasta')) $query->where('fecha', '<=', $request->hasta);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        $ventas = $query->orderByDesc('created_at')->paginate(15);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes  = Cliente::where('activo', true)->get(['id', 'nombre', 'apellido', 'codigo']);
        $productos = Producto::where('activo', true)->with('variantes')->get();
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'       => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad'    => 'required|integer|min:1',
            'metodo_pago' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Número único a nivel de plataforma: incluye el id de la tienda para
            // evitar colisiones con el índice global UNIQUE de "numero_venta".
            $tid    = auth()->user()->tienda_id ?? 0;
            $numero = 'VTA-' . $tid . '-' . Carbon::now()->format('Ymd') . '-' . str_pad(Venta::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['precio_unitario'] * $item['cantidad'] - ($item['descuento'] ?? 0);
            }
            $igv   = $subtotal * 0.18;
            $total = $subtotal + $igv;

            // Serie y correlativo según la configuración de la tienda
            $tienda = auth()->user()->tienda;
            $tipoComprobante = $request->tipo_comprobante ?? 'boleta';
            $serie = $tipoComprobante === 'factura'
                ? ($tienda->serie_factura ?? 'F001')
                : ($tienda->serie_boleta ?? 'B001');
            $correlativo = str_pad(
                Venta::where('serie', $serie)->count() + 1,
                6, '0', STR_PAD_LEFT
            );

            $venta = Venta::create([
                'numero_venta'    => $numero,
                'cliente_id'      => $request->cliente_id ?: null,
                'user_id'         => Auth::id(),
                'fecha'           => today(),
                'tipo_comprobante'=> $tipoComprobante,
                'serie'           => $serie,
                'correlativo'     => $correlativo,
                'subtotal'        => $subtotal,
                'igv'             => $igv,
                'total'           => $total,
                'descuento'       => $request->descuento_global ?? 0,
                'metodo_pago'     => $request->metodo_pago,
                'monto_pagado'    => $request->monto_pagado ?? $total,
                'vuelto'          => max(0, ($request->monto_pagado ?? $total) - $total),
                'estado'          => 'completada',
                'observaciones'   => $request->observaciones,
            ]);

            foreach ($request->items as $item) {
                DetalleVenta::create([
                    'venta_id'       => $venta->id,
                    'producto_id'    => $item['producto_id'],
                    'variante_id'    => $item['variante_id'] ?? null,
                    'producto_nombre'=> $item['nombre'],
                    'talla'          => $item['talla'] ?? null,
                    'color'          => $item['color'] ?? null,
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario'=> $item['precio_unitario'],
                    'descuento'      => $item['descuento'] ?? 0,
                    'subtotal'       => $item['precio_unitario'] * $item['cantidad'] - ($item['descuento'] ?? 0),
                ]);

                // Actualizar stock (con bloqueo y validación para evitar sobreventa)
                if (!empty($item['variante_id'])) {
                    $variante = ProductoVariante::lockForUpdate()->find($item['variante_id']);
                    if ($variante) {
                        if ($variante->stock < $item['cantidad']) {
                            throw new \RuntimeException(
                                "Stock insuficiente de «{$item['nombre']}» ({$item['talla']}/{$item['color']}). Disponible: {$variante->stock}."
                            );
                        }
                        $variante->decrement('stock', $item['cantidad']);
                    }
                }
            }

            DB::commit();

            // Facturación electrónica automática (opcional; no debe romper la venta).
            if (config('facturacion.habilitado') && config('facturacion.auto_emitir')) {
                try {
                    app(\App\Facturacion\ServicioFacturacion::class)->emitirDesdeVenta($venta);
                } catch (\Throwable $e) {
                    // Se registra el fallo pero la venta ya está confirmada.
                    report($e);
                }
            }

            return redirect()->route('ventas.show', $venta)->with('success', 'Venta registrada: ' . $numero);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load('cliente', 'vendedor', 'detalles.producto');
        return view('ventas.show', compact('venta'));
    }

    /** Comprobante imprimible (ticket / boleta) en una página independiente. */
    public function imprimir(Venta $venta)
    {
        $venta->load('cliente', 'vendedor', 'detalles', 'tienda');
        return view('ventas.imprimir', compact('venta'));
    }

    public function anular(Venta $venta)
    {
        if ($venta->estado === 'anulada') {
            return back()->with('error', 'La venta ya está anulada.');
        }
        DB::beginTransaction();
        try {
            $venta->update(['estado' => 'anulada']);
            // Devolver stock
            foreach ($venta->detalles as $d) {
                if ($d->variante_id) {
                    ProductoVariante::where('id', $d->variante_id)->increment('stock', $d->cantidad);
                }
            }
            DB::commit();
            return back()->with('success', 'Venta anulada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular: ' . $e->getMessage());
        }
    }
}

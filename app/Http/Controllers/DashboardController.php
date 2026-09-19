<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\ProductoVariante;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy       = Carbon::today();
        $mesActual = Carbon::now()->startOfMonth();
        $tiendaId  = auth()->user()->tienda_id;

        // KPIs — con filtro tienda
        $ventasHoy = Venta::where('estado', 'completada')
            ->whereDate('fecha', $hoy)
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->sum('total');

        $ventasMes = Venta::where('estado', 'completada')
            ->where('fecha', '>=', $mesActual)
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->sum('total');

        $totalClientes = Cliente::where('activo', true)
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->count();

        $totalProductos = Producto::where('activo', true)
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->count();

        // Stock bajo — con filtro tienda a través de relación
        $stockBajo = ProductoVariante::where('stock', '<=', DB::raw('stock_minimo'))
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->with('producto')
            ->count();

        // Ventas últimos 7 días para gráfico — con filtro tienda
        $ventas7dias = Venta::where('estado', 'completada')
            ->where('fecha', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->select(DB::raw('DATE(fecha) as dia'), DB::raw('SUM(total) as total'))
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $dia      = Carbon::now()->subDays($i);
            $labels[] = $dia->locale('es')->isoFormat('ddd');   // nombre día en español
            $found    = $ventas7dias->firstWhere('dia', $dia->format('Y-m-d'));
            $data[]   = $found ? (float) $found->total : 0;
        }

        // Top productos vendidos (mes actual)
        $topProductos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->where('ventas.estado', 'completada')
            ->where('ventas.fecha', '>=', $mesActual)
            ->when($tiendaId, fn ($q) => $q->where('ventas.tienda_id', $tiendaId))
            ->select('productos.nombre', DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)->get();

        // Ventas por categoría (dona) — mes actual
        $ventasPorCategoria = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->where('ventas.estado', 'completada')
            ->where('ventas.fecha', '>=', $mesActual)
            ->when($tiendaId, fn ($q) => $q->where('ventas.tienda_id', $tiendaId))
            ->select('categorias.nombre', DB::raw('SUM(detalle_ventas.subtotal) as total'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('total')->limit(5)->get();

        // Ventas por método de pago (mes actual)
        $metodos = Venta::where('estado', 'completada')
            ->where('fecha', '>=', $mesActual)
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->select('metodo_pago', DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')->orderByDesc('total')->get();
        $metodoLabels = $metodos->pluck('metodo_pago')->map(fn ($m) => ucfirst($m));
        $metodoData   = $metodos->pluck('total');

        // Stock por categoría (inventario)
        $stockCat = DB::table('producto_variantes')
            ->join('productos', 'producto_variantes.producto_id', '=', 'productos.id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->when($tiendaId, fn ($q) => $q->where('productos.tienda_id', $tiendaId))
            ->select('categorias.nombre', DB::raw('SUM(producto_variantes.stock) as stock'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('stock')->limit(8)->get();
        $stockCatLabels = $stockCat->pluck('nombre');
        $stockCatData   = $stockCat->pluck('stock');

        // Últimas ventas
        $ultimasVentas = Venta::with('cliente')
            ->when($tiendaId, fn ($q) => $q->where('tienda_id', $tiendaId))
            ->orderByDesc('created_at')->limit(8)->get();

        return view('dashboard.index', compact(
            'ventasHoy', 'ventasMes', 'totalClientes', 'totalProductos',
            'stockBajo', 'labels', 'data', 'topProductos',
            'ventasPorCategoria', 'ultimasVentas',
            'metodoLabels', 'metodoData', 'stockCatLabels', 'stockCatData'
        ));
    }
}

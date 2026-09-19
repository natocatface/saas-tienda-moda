<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    // ===================== VENTAS =====================

    public function ventas(Request $request)
    {
        [$desde, $hasta, $ventas, $totalGeneral, $totalVentas, $porMetodoPago] = $this->datosVentas($request);

        return view('reportes.ventas', compact('ventas', 'totalGeneral', 'totalVentas', 'porMetodoPago', 'desde', 'hasta'));
    }

    public function ventasImprimir(Request $request)
    {
        [$desde, $hasta, $ventas, $totalGeneral, $totalVentas, $porMetodoPago] = $this->datosVentas($request);
        $tienda = auth()->user()->tienda;

        return view('reportes.ventas_print', compact('ventas', 'totalGeneral', 'totalVentas', 'porMetodoPago', 'desde', 'hasta', 'tienda'));
    }

    public function ventasCsv(Request $request): StreamedResponse
    {
        [$desde, $hasta, $ventas] = $this->datosVentas($request);

        $nombre = 'reporte_ventas_' . $desde . '_a_' . $hasta . '.csv';

        return $this->descargarCsv($nombre, function ($out) use ($ventas) {
            fputcsv($out, ['N° Venta', 'Serie-Corr.', 'Fecha', 'Cliente', 'Vendedor', 'Comprobante', 'Método', 'Subtotal', 'IGV', 'Total']);
            foreach ($ventas as $v) {
                fputcsv($out, [
                    $v->numero_venta,
                    trim(($v->serie ?? '') . '-' . ($v->correlativo ?? '')),
                    $v->fecha->format('d/m/Y'),
                    $v->cliente ? $v->cliente->nombre . ' ' . $v->cliente->apellido : 'Directo',
                    $v->vendedor->name ?? '—',
                    ucfirst($v->tipo_comprobante),
                    ucfirst($v->metodo_pago),
                    number_format($v->subtotal, 2, '.', ''),
                    number_format($v->igv, 2, '.', ''),
                    number_format($v->total, 2, '.', ''),
                ]);
            }
        });
    }

    private function datosVentas(Request $request): array
    {
        $desde = $request->desde ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $hasta = $request->hasta ?? Carbon::now()->format('Y-m-d');

        $ventas = Venta::where('estado', 'completada')
            ->whereBetween('fecha', [$desde, $hasta])
            ->with('cliente', 'vendedor')
            ->orderBy('fecha')
            ->get();

        $totalGeneral  = $ventas->sum('total');
        $totalVentas   = $ventas->count();
        $porMetodoPago = $ventas->groupBy('metodo_pago')->map->sum('total');

        return [$desde, $hasta, $ventas, $totalGeneral, $totalVentas, $porMetodoPago];
    }

    // ===================== INVENTARIO =====================

    public function inventario(Request $request)
    {
        [$productos, $stockBajo] = $this->datosInventario($request);

        return view('reportes.inventario', compact('productos', 'stockBajo'));
    }

    public function inventarioImprimir(Request $request)
    {
        [$productos, $stockBajo] = $this->datosInventario($request);
        $tienda = auth()->user()->tienda;

        return view('reportes.inventario_print', compact('productos', 'stockBajo', 'tienda'));
    }

    public function inventarioCsv(Request $request): StreamedResponse
    {
        [$productos] = $this->datosInventario($request);

        return $this->descargarCsv('reporte_inventario_' . date('Y-m-d') . '.csv', function ($out) use ($productos) {
            fputcsv($out, ['Código', 'Producto', 'Categoría', 'Género', 'Variantes', 'Stock Total', 'Precio Compra', 'Valor Inventario', 'Estado']);
            foreach ($productos as $p) {
                $stockTotal = $p->variantes->sum('stock');
                $valorInv   = $stockTotal * $p->precio_compra;
                $estado     = $stockTotal <= 0
                    ? 'Sin stock'
                    : ($p->variantes->some(fn ($v) => $v->stock <= $v->stock_minimo) ? 'Stock bajo' : 'OK');

                fputcsv($out, [
                    $p->codigo,
                    $p->nombre,
                    $p->categoria->nombre ?? '—',
                    ucfirst($p->genero),
                    $p->variantes->count(),
                    $stockTotal,
                    number_format($p->precio_compra, 2, '.', ''),
                    number_format($valorInv, 2, '.', ''),
                    $estado,
                ]);
            }
        });
    }

    private function datosInventario(Request $request): array
    {
        $query = Producto::with(['categoria', 'variantes'])->where('activo', true);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $productos = $query->get();
        $stockBajo = $productos->filter(fn ($p) => $p->variantes->some(fn ($v) => $v->stock <= $v->stock_minimo));

        return [$productos, $stockBajo];
    }

    // ===================== UTILIDAD =====================

    private function descargarCsv(string $nombre, callable $escribir): StreamedResponse
    {
        return response()->streamDownload(function () use ($escribir) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 para que Excel respete los acentos
            fwrite($out, "\xEF\xBB\xBF");
            $escribir($out);
            fclose($out);
        }, $nombre, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

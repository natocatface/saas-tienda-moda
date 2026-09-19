@php
    $sim = $tienda->simbolo_moneda ?? 'S/';
    $valorTotal = $productos->sum(fn ($p) => $p->variantes->sum('stock') * $p->precio_compra);
    $unidades   = $productos->sum(fn ($p) => $p->variantes->sum('stock'));
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte de Inventario {{ now()->format('Y-m-d') }}</title>
<style>
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:'Segoe UI',Arial,sans-serif; color:#1f2937; background:#fff; padding:28px; font-size:12px; }
    .toolbar { margin-bottom:18px; }
    .toolbar button { background:#e8398c; color:#fff; border:none; padding:9px 18px; border-radius:8px; font-weight:600; cursor:pointer; font-size:13px; }
    .toolbar a { margin-left:8px; color:#444; text-decoration:none; font-size:13px; }
    header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #e8398c; padding-bottom:14px; margin-bottom:18px; }
    .tienda { font-size:18px; font-weight:700; color:#111; }
    .muted { color:#6b7280; }
    h1 { font-size:16px; margin-bottom:2px; }
    .kpis { display:flex; gap:14px; margin-bottom:18px; flex-wrap:wrap; }
    .kpi { border:1px solid #e5e7eb; border-radius:8px; padding:10px 16px; min-width:130px; }
    .kpi .l { font-size:10px; text-transform:uppercase; color:#6b7280; }
    .kpi .v { font-size:18px; font-weight:700; }
    table { width:100%; border-collapse:collapse; }
    th { background:#fce7f3; text-align:left; padding:7px 8px; font-size:11px; border-bottom:1px solid #f3aacf; }
    td { padding:6px 8px; border-bottom:1px solid #eef0f2; font-size:11px; }
    td.r, th.r { text-align:right; }
    td.c, th.c { text-align:center; }
    tfoot td { font-weight:700; border-top:2px solid #111; }
    .est-bajo { color:#c2410c; font-weight:700; }
    .est-sin { color:#dc2626; font-weight:700; }
    @media print { body { padding:0; } .toolbar { display:none; } @page { margin:12mm; size:A4 landscape; } }
</style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
        <a href="{{ route('reportes.inventario', request()->only('categoria_id')) }}">Volver</a>
    </div>

    <header>
        <div>
            <div class="tienda">{{ $tienda->nombre ?? 'Mi Tienda' }}</div>
            @if($tienda?->ruc)<div class="muted">RUC: {{ $tienda->ruc }}</div>@endif
        </div>
        <div style="text-align:right;">
            <h1>Reporte de Inventario</h1>
            <div class="muted">Emitido: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </header>

    <div class="kpis">
        <div class="kpi"><div class="l">Productos</div><div class="v">{{ $productos->count() }}</div></div>
        <div class="kpi"><div class="l">Unidades</div><div class="v">{{ $unidades }}</div></div>
        <div class="kpi"><div class="l">Stock bajo/agotado</div><div class="v">{{ $stockBajo->count() }}</div></div>
        <div class="kpi"><div class="l">Valor inventario</div><div class="v">{{ $sim }} {{ number_format($valorTotal,2) }}</div></div>
    </div>

    <table>
        <thead>
            <tr><th>Código</th><th>Producto</th><th>Categoría</th><th>Género</th><th class="c">Variantes</th><th class="c">Stock</th><th class="r">Valor</th><th>Estado</th></tr>
        </thead>
        <tbody>
            @forelse($productos as $p)
            @php $st = $p->variantes->sum('stock'); $val = $st * $p->precio_compra; @endphp
            <tr>
                <td>{{ $p->codigo }}</td>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->categoria->nombre ?? '—' }}</td>
                <td>{{ ucfirst($p->genero) }}</td>
                <td class="c">{{ $p->variantes->count() }}</td>
                <td class="c">{{ $st }}</td>
                <td class="r">{{ $sim }} {{ number_format($val,2) }}</td>
                <td>
                    @if($st <= 0)<span class="est-sin">Sin stock</span>
                    @elseif($p->variantes->some(fn($v) => $v->stock <= $v->stock_minimo))<span class="est-bajo">Stock bajo</span>
                    @else OK @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:30px;color:#6b7280;">Sin productos</td></tr>
            @endforelse
        </tbody>
        @if($productos->count())
        <tfoot>
            <tr><td colspan="5" class="r">TOTALES</td><td class="c">{{ $unidades }}</td><td class="r">{{ $sim }} {{ number_format($valorTotal,2) }}</td><td></td></tr>
        </tfoot>
        @endif
    </table>

    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 350));</script>
</body>
</html>

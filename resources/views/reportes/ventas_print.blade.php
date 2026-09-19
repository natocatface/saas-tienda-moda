@php $sim = $tienda->simbolo_moneda ?? 'S/'; @endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte de Ventas {{ $desde }} a {{ $hasta }}</title>
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
    tfoot td { font-weight:700; border-top:2px solid #111; }
    @media print { body { padding:0; } .toolbar { display:none; } @page { margin:12mm; size:A4; } }
</style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
        <a href="{{ route('reportes.ventas', request()->only('desde','hasta')) }}">Volver</a>
    </div>

    <header>
        <div>
            <div class="tienda">{{ $tienda->nombre ?? 'Mi Tienda' }}</div>
            @if($tienda?->ruc)<div class="muted">RUC: {{ $tienda->ruc }}</div>@endif
        </div>
        <div style="text-align:right;">
            <h1>Reporte de Ventas</h1>
            <div class="muted">Del {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</div>
            <div class="muted">Emitido: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </header>

    <div class="kpis">
        <div class="kpi"><div class="l">Total ventas</div><div class="v">{{ $totalVentas }}</div></div>
        <div class="kpi"><div class="l">Ingresos</div><div class="v">{{ $sim }} {{ number_format($totalGeneral,2) }}</div></div>
        @foreach($porMetodoPago as $metodo => $monto)
        <div class="kpi"><div class="l">{{ ucfirst($metodo) }}</div><div class="v">{{ $sim }} {{ number_format($monto,2) }}</div></div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr><th>N° Venta</th><th>Comprobante</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Método</th><th class="r">Total</th></tr>
        </thead>
        <tbody>
            @forelse($ventas as $v)
            <tr>
                <td>{{ $v->numero_venta }}</td>
                <td>{{ ucfirst($v->tipo_comprobante) }} {{ $v->serie }}-{{ $v->correlativo }}</td>
                <td>{{ $v->fecha->format('d/m/Y') }}</td>
                <td>{{ $v->cliente ? $v->cliente->nombre.' '.$v->cliente->apellido : 'Directo' }}</td>
                <td>{{ $v->vendedor->name ?? '—' }}</td>
                <td>{{ ucfirst($v->metodo_pago) }}</td>
                <td class="r">{{ $sim }} {{ number_format($v->total,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:#6b7280;">Sin ventas en el período</td></tr>
            @endforelse
        </tbody>
        @if($ventas->count())
        <tfoot>
            <tr><td colspan="6" class="r">TOTAL</td><td class="r">{{ $sim }} {{ number_format($totalGeneral,2) }}</td></tr>
        </tfoot>
        @endif
    </table>

    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 350));</script>
</body>
</html>

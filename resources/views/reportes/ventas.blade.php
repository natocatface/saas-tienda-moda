@extends('layouts.app')
@section('title','Reporte de Ventas')
@section('page-title','Reporte de Ventas')

@section('content')
<div class="page-header">
    <div><h1>Reporte de Ventas</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('reportes.ventas.csv', request()->only('desde','hasta')) }}" class="btn btn-outline"><i class="fa-solid fa-file-csv"></i> Excel (CSV)</a>
        <a href="{{ route('reportes.ventas.imprimir', request()->only('desde','hasta')) }}" target="_blank" class="btn btn-outline"><i class="fa-solid fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <form method="GET" class="form-row" style="align-items:flex-end;">
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="{{ $desde }}">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="{{ $hasta }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-chart-bar"></i> Generar</button>
    </form>
</div>

<!-- KPIs -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    <div class="card" style="text-align:center;">
        <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Total Ventas</div>
        <div style="font-size:28px;font-weight:700;color:var(--accent);">{{ $totalVentas }}</div>
    </div>
    <div class="card" style="text-align:center;">
        <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Ingresos</div>
        <div style="font-size:28px;font-weight:700;color:#16a34a;">S/ {{ number_format($totalGeneral,2) }}</div>
    </div>
    @foreach($porMetodoPago as $metodo => $monto)
    <div class="card" style="text-align:center;">
        <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);font-weight:600;margin-bottom:6px;">{{ ucfirst($metodo) }}</div>
        <div style="font-size:22px;font-weight:700;">S/ {{ number_format($monto,2) }}</div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>N° Venta</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Comprobante</th><th>Método</th><th>Total</th></tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                <tr>
                    <td><a href="{{ route('ventas.show',$v) }}" style="color:var(--accent);font-weight:600;">{{ $v->numero_venta }}</a></td>
                    <td>{{ $v->fecha->format('d/m/Y') }}</td>
                    <td>{{ $v->cliente ? $v->cliente->nombre.' '.$v->cliente->apellido : 'Directo' }}</td>
                    <td>{{ $v->vendedor->name ?? '—' }}</td>
                    <td>{{ ucfirst($v->tipo_comprobante) }}</td>
                    <td>{{ ucfirst($v->metodo_pago) }}</td>
                    <td><strong>S/ {{ number_format($v->total,2) }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">Sin ventas en el período seleccionado</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

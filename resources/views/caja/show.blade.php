@extends('layouts.app')
@section('title','Detalle de Caja')
@section('page-title','Detalle de Caja')

@section('content')
<div class="page-header">
    <div>
        <h1>Cierre de caja · {{ $caja->fecha?->format('d/m/Y') }}</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span><a href="{{ route('caja.index') }}">Caja</a><span class="breadcrumb-sep">›</span>Detalle</div>
    </div>
    <a href="{{ route('caja.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px;">
    <div class="card"><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Monto inicial</div><div style="font-size:20px;font-weight:700;">S/ {{ number_format($caja->monto_inicial,2) }}</div></div>
    <div class="card"><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Ventas del día</div><div style="font-size:20px;font-weight:700;color:#16a34a;">S/ {{ number_format($caja->total_ventas,2) }}</div></div>
    <div class="card"><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Egresos</div><div style="font-size:20px;font-weight:700;color:#dc2626;">S/ {{ number_format($caja->total_egresos,2) }}</div></div>
    <div class="card"><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Efectivo final</div><div style="font-size:20px;font-weight:700;color:var(--accent);">S/ {{ number_format($caja->monto_final,2) }}</div></div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div style="font-size:13px;color:var(--text-muted);line-height:2;">
        Responsable: <b>{{ $caja->usuario->name ?? '—' }}</b><br>
        Apertura: <b>{{ $caja->apertura?->format('d/m/Y H:i') }}</b> · Cierre: <b>{{ $caja->cierre?->format('d/m/Y H:i') ?? '—' }}</b><br>
        Estado: <span class="badge {{ $caja->estaAbierta() ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($caja->estado) }}</span><br>
        @if($caja->observaciones)<span style="display:block;margin-top:8px;">{{ $caja->observaciones }}</span>@endif
    </div>
</div>

<div class="card">
    <div class="card-header"><div class="card-title">Movimientos manuales</div></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tipo</th><th>Concepto</th><th>Responsable</th><th style="text-align:right;">Monto</th></tr></thead>
            <tbody>
                @forelse($caja->movimientos as $m)
                <tr>
                    <td><span class="badge {{ $m->tipo === 'ingreso' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($m->tipo) }}</span></td>
                    <td>{{ $m->concepto }}</td>
                    <td>{{ $m->usuario->name ?? '—' }}</td>
                    <td style="text-align:right;font-weight:600;color:{{ $m->tipo === 'ingreso' ? '#16a34a' : '#dc2626' }};">
                        {{ $m->tipo === 'ingreso' ? '+' : '−' }} S/ {{ number_format($m->monto,2) }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:30px;color:var(--text-muted);">Sin movimientos manuales</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

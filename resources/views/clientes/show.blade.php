@extends('layouts.app')
@section('title','Detalle Cliente')
@section('page-title','Detalle del Cliente')

@section('content')
<div class="page-header">
    <div><h1>{{ $cliente->nombre }} {{ $cliente->apellido }}</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> Editar</a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
    <div class="card">
        <div style="text-align:center;margin-bottom:20px;">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#e8398c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:28px;margin:0 auto 12px;">
                {{ strtoupper(substr($cliente->nombre,0,1).substr($cliente->apellido,0,1)) }}
            </div>
            <div style="font-size:18px;font-weight:700;">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
            <div style="font-size:12px;color:var(--text-muted);">{{ $cliente->codigo }}</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
            <div><span style="color:var(--text-muted);">DNI:</span> {{ $cliente->dni ?? '—' }}</div>
            <div><span style="color:var(--text-muted);">Email:</span> {{ $cliente->email ?? '—' }}</div>
            <div><span style="color:var(--text-muted);">Teléfono:</span> {{ $cliente->telefono ?? '—' }}</div>
            <div><span style="color:var(--text-muted);">Ciudad:</span> {{ $cliente->ciudad ?? '—' }}</div>
            <div><span style="color:var(--text-muted);">Puntos:</span> <strong style="color:var(--accent);">{{ number_format($cliente->puntos,0) }} pts</strong></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Historial de Compras</div></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>N° Venta</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead>
                <tbody>
                    @forelse($cliente->ventas as $v)
                    <tr>
                        <td><a href="{{ route('ventas.show',$v) }}" style="color:var(--accent);font-weight:600;">{{ $v->numero_venta }}</a></td>
                        <td>{{ $v->fecha->format('d/m/Y') }}</td>
                        <td><strong>S/ {{ number_format($v->total,2) }}</strong></td>
                        <td><span class="badge {{ $v->estado=='completada'?'badge-success':($v->estado=='anulada'?'badge-danger':'badge-warning') }}">{{ ucfirst($v->estado) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:30px;color:var(--text-muted);">Sin compras registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

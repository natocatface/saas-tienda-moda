@extends('layouts.app')
@section('title','Compras')
@section('page-title','Compras')

@section('content')
<div class="page-header">
    <div>
        <h1>Compras</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Compras</div>
    </div>
    <a href="{{ route('compras.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nueva Compra</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:20px;">
    <div class="card" style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0ea5a4,#16c0b0);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;"><i class="fa-solid fa-truck"></i></div>
        <div><div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;">Compras del mes</div><div style="font-size:22px;font-weight:700;">S/ {{ number_format($totalMes,2) }}</div></div>
    </div>
</div>

<div class="card">
    <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
        <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar N° de compra...">
        <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>N° Compra</th><th>Proveedor</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($compras as $c)
                <tr>
                    <td><code style="background:#f3f4f6;padding:3px 8px;border-radius:5px;font-size:11px;">{{ $c->numero_compra }}</code></td>
                    <td>{{ $c->proveedor->nombre ?? '—' }}</td>
                    <td>{{ $c->fecha->format('d/m/Y') }}</td>
                    <td style="font-weight:700;">S/ {{ number_format($c->total,2) }}</td>
                    <td><span class="badge {{ $c->estado=='recibido'?'badge-success':($c->estado=='anulado'?'badge-danger':'badge-warning') }}">{{ ucfirst($c->estado) }}</span></td>
                    <td><a href="{{ route('compras.show',$c) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">No hay compras registradas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $compras->withQueryString()->links() }}
</div>
@endsection

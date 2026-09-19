@extends('layouts.app')
@section('title','Stock')
@section('page-title','Stock')

@section('content')
<div class="page-header">
    <div>
        <h1>Control de Stock</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Stock</div>
    </div>
</div>

<!-- Resumen -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:20px;">
    <div class="card" style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3b50dd,#5b2fc9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div><div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;">Unidades totales</div><div style="font-size:22px;font-weight:700;">{{ number_format($totalUnidades) }}</div></div>
    </div>
    <div class="card" style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#f97316,#ef4444);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div><div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;">Stock bajo</div><div style="font-size:22px;font-weight:700;">{{ $bajoStock }}</div></div>
    </div>
    <div class="card" style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#6b7280,#374151);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;"><i class="fa-solid fa-ban"></i></div>
        <div><div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;">Sin stock</div><div style="font-size:22px;font-weight:700;">{{ $sinStock }}</div></div>
    </div>
</div>

<div class="card">
    <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
        <input type="text" name="buscar" class="form-control" style="flex:1;min-width:200px;" value="{{ request('buscar') }}" placeholder="Buscar producto, talla o color...">
        <select name="filtro" class="form-control" style="max-width:180px;">
            <option value="">Todos</option>
            <option value="bajo" {{ request('filtro')=='bajo'?'selected':'' }}>Solo stock bajo</option>
        </select>
        <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Filtrar</button>
        <a href="{{ route('stock.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Producto</th><th>Categoría</th><th>Talla</th><th>Color</th><th>Stock</th><th>Mínimo</th><th>Estado</th><th>Acción</th></tr>
            </thead>
            <tbody>
                @forelse($variantes as $v)
                @php
                    $estado = $v->stock == 0 ? ['Sin stock','badge-danger'] : ($v->stock <= $v->stock_minimo ? ['Bajo','badge-warning'] : ['Disponible','badge-success']);
                @endphp
                <tr>
                    <td style="font-weight:600;">{{ $v->producto->nombre ?? '—' }}<div style="font-size:11px;color:var(--text-muted);font-weight:400;">{{ $v->producto->codigo ?? '' }}</div></td>
                    <td>{{ $v->producto->categoria->nombre ?? '—' }}</td>
                    <td><span class="badge badge-info">{{ $v->talla }}</span></td>
                    <td>{{ $v->color }}</td>
                    <td><input type="number" form="stk-{{ $v->id }}" name="stock" value="{{ $v->stock }}" min="0" class="form-control" style="width:90px;padding:6px 8px;"></td>
                    <td><input type="number" form="stk-{{ $v->id }}" name="stock_minimo" value="{{ $v->stock_minimo }}" min="0" class="form-control" style="width:90px;padding:6px 8px;"></td>
                    <td><span class="badge {{ $estado[1] }}">{{ $estado[0] }}</span></td>
                    <td><button form="stk-{{ $v->id }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk"></i></button></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">No hay variantes de stock registradas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $variantes->withQueryString()->links() }}
</div>

@foreach($variantes as $v)
    <form id="stk-{{ $v->id }}" method="POST" action="{{ route('stock.update', $v) }}">@csrf</form>
@endforeach
@endsection

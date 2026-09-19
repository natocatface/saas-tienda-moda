@extends('layouts.app')
@section('title','Reporte de Inventario')
@section('page-title','Reporte de Inventario')

@section('content')
<div class="page-header">
    <div><h1>Inventario General</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('reportes.inventario.csv', request()->only('categoria_id')) }}" class="btn btn-outline"><i class="fa-solid fa-file-csv"></i> Excel (CSV)</a>
        <a href="{{ route('reportes.inventario.imprimir', request()->only('categoria_id')) }}" target="_blank" class="btn btn-outline"><i class="fa-solid fa-file-pdf"></i> PDF</a>
    </div>
</div>

@if($stockBajo->count() > 0)
<div class="alert alert-warning">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <strong>{{ $stockBajo->count() }} producto(s)</strong> tienen stock bajo o agotado. Revisar y reponer.
</div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Código</th><th>Producto</th><th>Categoría</th><th>Género</th><th>Variantes</th><th>Stock Total</th><th>Valor Inventario</th><th>Estado</th></tr>
            </thead>
            <tbody>
                @forelse($productos as $p)
                @php $stockTotal = $p->variantes->sum('stock'); $valorInv = $stockTotal * $p->precio_compra; @endphp
                <tr>
                    <td><code style="font-size:11px;background:#f3f4f6;padding:3px 8px;border-radius:5px;">{{ $p->codigo }}</code></td>
                    <td><strong>{{ $p->nombre }}</strong></td>
                    <td>{{ $p->categoria->nombre ?? '—' }}</td>
                    <td>{{ ucfirst($p->genero) }}</td>
                    <td>{{ $p->variantes->count() }}</td>
                    <td>
                        <span class="badge {{ $stockTotal <= 0 ? 'badge-danger' : ($stockTotal <= 10 ? 'badge-warning' : 'badge-success') }}">
                            {{ $stockTotal }} uds.
                        </span>
                    </td>
                    <td>S/ {{ number_format($valorInv,2) }}</td>
                    <td>
                        @if($stockTotal <= 0) <span class="badge badge-danger">Sin stock</span>
                        @elseif($p->variantes->some(fn($v) => $v->stock <= $v->stock_minimo)) <span class="badge badge-warning">Stock bajo</span>
                        @else <span class="badge badge-success">OK</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">Sin productos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

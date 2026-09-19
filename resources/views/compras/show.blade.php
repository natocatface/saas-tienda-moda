@extends('layouts.app')
@section('title','Detalle de Compra')
@section('page-title','Detalle de Compra')

@section('content')
<div class="page-header">
    <div>
        <h1>Compra {{ $compra->numero_compra }}</h1>
        <div class="breadcrumb"><a href="{{ route('compras.index') }}">Compras</a><span class="breadcrumb-sep">›</span>Detalle</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('compras.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        @if($compra->estado !== 'anulado')
        <form method="POST" action="{{ route('compras.anular', $compra) }}" onsubmit="return confirm('¿Anular esta compra? Se revertirá el stock ingresado.');">
            @csrf
            <button class="btn btn-danger"><i class="fa-solid fa-ban"></i> Anular compra</button>
        </form>
        @endif
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="form-row">
        <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Proveedor</div><div style="font-weight:600;">{{ $compra->proveedor->nombre ?? '—' }}</div></div>
        <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Fecha</div><div style="font-weight:600;">{{ $compra->fecha->format('d/m/Y') }}</div></div>
        <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Registrado por</div><div style="font-weight:600;">{{ $compra->usuario->name ?? '—' }}</div></div>
        <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Estado</div><span class="badge {{ $compra->estado === 'anulado' ? 'badge-danger' : 'badge-success' }}">{{ ucfirst($compra->estado) }}</span></div>
    </div>
    @if($compra->observaciones)
    <div style="margin-top:14px;font-size:13px;color:var(--text-muted);"><b>Observaciones:</b> {{ $compra->observaciones }}</div>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($compra->detalles as $d)
                <tr>
                    <td style="font-weight:600;">{{ $d->producto->nombre ?? '—' }}</td>
                    <td>{{ $d->variante ? $d->variante->talla.' / '.$d->variante->color : '—' }}</td>
                    <td>{{ $d->cantidad }}</td>
                    <td>S/ {{ number_format($d->precio_unitario,2) }}</td>
                    <td style="font-weight:600;">S/ {{ number_format($d->subtotal,2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="4" style="text-align:right;font-weight:600;">Total</td><td style="font-weight:700;color:var(--accent);">S/ {{ number_format($compra->total,2) }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

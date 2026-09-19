@extends('layouts.app')
@section('title','Configuración')
@section('page-title','Configuración')

@section('content')
<div class="page-header">
    <div>
        <h1>Configuración</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Configuración</div>
    </div>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-gear" style="color:var(--accent);margin-right:6px;"></i>Preferencias de venta</div></div>
    <form method="POST" action="{{ route('configuracion.update') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">IGV / Impuesto (%)</label>
                <input type="number" step="0.01" name="igv" class="form-control" value="{{ old('igv', $tienda->igv ?? 18) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Moneda (código)</label>
                <input type="text" name="moneda" class="form-control" value="{{ old('moneda', $tienda->moneda ?? 'PEN') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Símbolo</label>
                <input type="text" name="simbolo_moneda" class="form-control" value="{{ old('simbolo_moneda', $tienda->simbolo_moneda ?? 'S/') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Serie de boleta</label>
                <input type="text" name="serie_boleta" class="form-control" value="{{ old('serie_boleta', $tienda->serie_boleta ?? 'B001') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Serie de factura</label>
                <input type="text" name="serie_factura" class="form-control" value="{{ old('serie_factura', $tienda->serie_factura ?? 'F001') }}" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar configuración</button>
    </form>
</div>
@endsection

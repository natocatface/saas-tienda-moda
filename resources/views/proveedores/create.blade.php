@extends('layouts.app')
@section('title','Nuevo Proveedor')
@section('page-title','Nuevo Proveedor')

@section('content')
<div class="page-header">
    <div><h1>Nuevo Proveedor</h1></div>
    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('proveedores.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Razón Social / Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">RUC</label>
                <input type="text" name="ruc" class="form-control" value="{{ old('ruc') }}" maxlength="11">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Persona de Contacto</label>
                <input type="text" name="contacto" class="form-control" value="{{ old('contacto') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
    </form>
</div>
@endsection

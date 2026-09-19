@extends('layouts.app')
@section('title','Editar Proveedor')
@section('page-title','Editar Proveedor')

@section('content')
<div class="page-header">
    <div><h1>Editar Proveedor</h1></div>
    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('proveedores.update',$proveedor) }}">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Razón Social / Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$proveedor->nombre) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">RUC</label>
                <input type="text" name="ruc" class="form-control" value="{{ old('ruc',$proveedor->ruc) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Contacto</label>
                <input type="text" name="contacto" class="form-control" value="{{ old('contacto',$proveedor->contacto) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono',$proveedor->telefono) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email',$proveedor->email) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad',$proveedor->ciudad) }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion',$proveedor->direccion) }}">
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Actualizar</button>
        </div>
    </form>
</div>
@endsection

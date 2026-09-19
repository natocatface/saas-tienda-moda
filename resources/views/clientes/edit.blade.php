@extends('layouts.app')
@section('title','Editar Cliente')
@section('page-title','Editar Cliente')

@section('content')
<div class="page-header">
    <div><h1>Editar Cliente</h1></div>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('clientes.update',$cliente) }}">
        @csrf @method('PUT')
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre',$cliente->nombre) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Apellido *</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido',$cliente->apellido) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni',$cliente->dni) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email',$cliente->email) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono',$cliente->telefono) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad',$cliente->ciudad) }}">
            </div>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Actualizar</button>
        </div>
    </form>
</div>
@endsection

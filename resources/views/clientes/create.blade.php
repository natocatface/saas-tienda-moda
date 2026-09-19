@extends('layouts.app')
@section('title','Nuevo Cliente')
@section('page-title','Nuevo Cliente')

@section('content')
<div class="page-header">
    <div><h1>Nuevo Cliente</h1></div>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                @error('nombre')<small style="color:#e8398c;">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Apellido *</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" maxlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')<small style="color:#e8398c;">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Género</label>
                <select name="genero" class="form-control">
                    <option value="">No especificado</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:10px;">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
    </form>
</div>
@endsection

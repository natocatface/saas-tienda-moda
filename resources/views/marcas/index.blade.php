@extends('layouts.app')
@section('title','Marcas')
@section('page-title','Marcas')

@section('content')
<div class="page-header">
    <div>
        <h1>Marcas</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Marcas</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;" class="cat-grid">
    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid {{ $editar ? 'fa-pen' : 'fa-plus' }}" style="color:var(--accent);margin-right:6px;"></i>{{ $editar ? 'Editar marca' : 'Nueva marca' }}</div>
        </div>
        <form method="POST" action="{{ $editar ? route('marcas.update', $editar) : route('marcas.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre de la marca</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $editar->nombre ?? '') }}" placeholder="Ej. Nike" required>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="activo" value="1" id="activo" {{ old('activo', $editar->activo ?? true) ? 'checked' : '' }}>
                <label for="activo" style="margin:0;">Activa</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-floppy-disk"></i> {{ $editar ? 'Guardar cambios' : 'Crear marca' }}</button>
            @if($editar)<a href="{{ route('marcas.index') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;text-align:center;">Cancelar</a>@endif
        </form>
    </div>

    <!-- Lista -->
    <div class="card">
        <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar marca...">
            <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Marca</th><th>Productos</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($marcas as $m)
                    <tr>
                        <td style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#7c3aed,#e8398c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;">{{ strtoupper(substr($m->nombre,0,2)) }}</div>
                            <span style="font-weight:600;">{{ $m->nombre }}</span>
                        </td>
                        <td>{{ $m->productos_count }}</td>
                        <td><span class="badge {{ $m->activo ? 'badge-success' : 'badge-danger' }}">{{ $m->activo ? 'Activa' : 'Inactiva' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('marcas.index', ['edit'=>$m->id]) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                                <form method="POST" action="{{ route('marcas.destroy', $m) }}" onsubmit="return confirm('¿Eliminar esta marca?');">
                                    @csrf
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted);">No hay marcas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.cat-grid{grid-template-columns:1fr !important;}}</style>
@endsection

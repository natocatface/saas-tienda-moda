@extends('layouts.app')
@section('title','Subcategorías')
@section('page-title','Subcategorías')

@section('content')
<div class="page-header">
    <div>
        <h1>Subcategorías</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span><a href="{{ route('categorias.index') }}">Categorías</a><span class="breadcrumb-sep">›</span>Subcategorías</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;" class="sub-grid">
    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid {{ $editar ? 'fa-pen' : 'fa-plus' }}" style="color:var(--accent);margin-right:6px;"></i>{{ $editar ? 'Editar subcategoría' : 'Nueva subcategoría' }}</div>
        </div>
        <form method="POST" action="{{ $editar ? route('subcategorias.update', $editar) : route('subcategorias.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">Selecciona…</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id', $editar->categoria_id ?? '')==$cat->id?'selected':'' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $editar->nombre ?? '') }}" placeholder="Ej. Camisas manga larga" required>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="activo" value="1" id="activo" {{ old('activo', $editar->activo ?? true) ? 'checked' : '' }}>
                <label for="activo" style="margin:0;">Activa</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-floppy-disk"></i> {{ $editar ? 'Guardar cambios' : 'Crear subcategoría' }}</button>
            @if($editar)<a href="{{ route('subcategorias.index') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;text-align:center;">Cancelar</a>@endif
        </form>
    </div>

    <!-- Lista -->
    <div class="card">
        <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
            <select name="categoria_id" class="form-control" style="max-width:200px;">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id')==$cat->id?'selected':'' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar subcategoría...">
            <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Subcategoría</th><th>Categoría</th><th>Productos</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($subcategorias as $s)
                    <tr>
                        <td style="font-weight:600;">{{ $s->nombre }}</td>
                        <td><span class="badge badge-info">{{ $s->categoria->nombre ?? '—' }}</span></td>
                        <td>{{ $s->productos_count }}</td>
                        <td><span class="badge {{ $s->activo ? 'badge-success' : 'badge-danger' }}">{{ $s->activo ? 'Activa' : 'Inactiva' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('subcategorias.index', ['edit'=>$s->id]) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                                <form method="POST" action="{{ route('subcategorias.destroy', $s) }}" onsubmit="return confirm('¿Eliminar esta subcategoría?');">
                                    @csrf
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No hay subcategorías registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.sub-grid{grid-template-columns:1fr !important;}}</style>
@endsection

@extends('layouts.app')
@section('title','Categorías')
@section('page-title','Categorías')

@section('content')
<div class="page-header">
    <div>
        <h1>Categorías</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Categorías</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;" class="cat-grid">
    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid {{ $editar ? 'fa-pen' : 'fa-plus' }}" style="color:var(--accent);margin-right:6px;"></i>{{ $editar ? 'Editar categoría' : 'Nueva categoría' }}</div>
        </div>
        <form method="POST" action="{{ $editar ? route('categorias.update', $editar) : route('categorias.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $editar->nombre ?? '') }}" placeholder="Ej. Camisas" required>
            </div>
            <div class="form-group">
                <label class="form-label">Género</label>
                <select name="genero" class="form-control" required>
                    @foreach(['damas'=>'Damas','caballeros'=>'Caballeros','ninos'=>'Niños','unisex'=>'Unisex'] as $v=>$t)
                        <option value="{{ $v }}" {{ old('genero', $editar->genero ?? 'unisex')==$v?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $editar->descripcion ?? '') }}" placeholder="Opcional">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="activo" value="1" id="activo" {{ old('activo', $editar->activo ?? true) ? 'checked' : '' }}>
                <label for="activo" style="margin:0;">Activa</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-floppy-disk"></i> {{ $editar ? 'Guardar cambios' : 'Crear categoría' }}</button>
            @if($editar)<a href="{{ route('categorias.index') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;text-align:center;">Cancelar</a>@endif
        </form>
    </div>

    <!-- Lista -->
    <div class="card">
        <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar categoría...">
            <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nombre</th><th>Género</th><th>Productos</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($categorias as $c)
                    <tr>
                        <td style="font-weight:600;">{{ $c->nombre }}<div style="font-size:11px;color:var(--text-muted);font-weight:400;">{{ $c->descripcion }}</div></td>
                        <td><span class="badge badge-info">{{ ucfirst($c->genero) }}</span></td>
                        <td>{{ $c->productos_count }}</td>
                        <td><span class="badge {{ $c->activo ? 'badge-success' : 'badge-danger' }}">{{ $c->activo ? 'Activa' : 'Inactiva' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('categorias.index', ['edit'=>$c->id]) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                                <form method="POST" action="{{ route('categorias.destroy', $c) }}" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    @csrf
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No hay categorías registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.cat-grid{grid-template-columns:1fr !important;}}</style>
@endsection

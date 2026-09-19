@extends('layouts.app')
@section('title','Usuarios')
@section('page-title','Usuarios')

@section('content')
<div class="page-header">
    <div>
        <h1>Usuarios del equipo</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Usuarios</div>
    </div>
    @if($tienda && $tienda->plan)
    <span class="badge badge-info" style="font-size:13px;">
        {{ $usuarios->count() }} / {{ $tienda->plan->max_usuarios == -1 ? '∞' : $tienda->plan->max_usuarios }} usuarios (Plan {{ $tienda->plan->nombre }})
    </span>
    @endif
</div>

<div style="display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;" class="cat-grid">
    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid {{ $editar ? 'fa-pen' : 'fa-user-plus' }}" style="color:var(--accent);margin-right:6px;"></i>{{ $editar ? 'Editar usuario' : 'Nuevo usuario' }}</div>
        </div>
        <form method="POST" action="{{ $editar ? route('usuarios.update', $editar) : route('usuarios.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $editar->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $editar->email ?? '') }}" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select name="rol_id" class="form-control" required>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ old('rol_id', $editar->rol_id ?? '')==$r->id?'selected':'' }}>{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $editar->telefono ?? '') }}" placeholder="Opcional">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña {{ $editar ? '(dejar vacío para no cambiar)' : '' }}</label>
                <input type="password" name="password" class="form-control" {{ $editar ? '' : 'required' }}>
            </div>
            <div class="form-group">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" {{ $editar ? '' : 'required' }}>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-floppy-disk"></i> {{ $editar ? 'Guardar cambios' : 'Crear usuario' }}</button>
            @if($editar)<a href="{{ route('usuarios.index') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;text-align:center;">Cancelar</a>@endif
        </form>
    </div>

    <!-- Lista -->
    <div class="card">
        <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;">
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar por nombre o correo...">
            <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Usuario</th><th>Rol</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($usuarios as $u)
                    <tr>
                        <td style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#e8398c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;">{{ strtoupper(substr($u->name,0,2)) }}</div>
                            <div><div style="font-weight:600;">{{ $u->name }}</div><div style="font-size:11px;color:var(--text-muted);">{{ $u->email }}</div></div>
                        </td>
                        <td><span class="badge badge-pink">{{ $u->rol->nombre ?? '—' }}</span></td>
                        <td>{{ $u->telefono ?? '—' }}</td>
                        <td><span class="badge {{ $u->activo ? 'badge-success' : 'badge-danger' }}">{{ $u->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('usuarios.index', ['edit'=>$u->id]) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('usuarios.toggle', $u) }}">
                                    @csrf
                                    <button class="btn btn-sm {{ $u->activo ? 'btn-danger' : 'btn-primary' }}"><i class="fa-solid {{ $u->activo ? 'fa-ban' : 'fa-check' }}"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No hay usuarios registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.cat-grid{grid-template-columns:1fr !important;}}</style>
@endsection

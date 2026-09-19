@extends('layouts.app')
@section('title','Proveedores')
@section('page-title','Proveedores')

@section('content')
<div class="page-header">
    <div><h1>Proveedores</h1></div>
    <a href="{{ route('proveedores.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo Proveedor</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:flex-end;">
        <div class="form-group" style="margin-bottom:0;flex:1;">
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Buscar por nombre o RUC...">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nombre</th><th>RUC</th><th>Contacto</th><th>Email</th><th>Teléfono</th><th>Ciudad</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($proveedores as $pv)
                <tr>
                    <td><strong>{{ $pv->nombre }}</strong></td>
                    <td>{{ $pv->ruc ?? '—' }}</td>
                    <td>{{ $pv->contacto ?? '—' }}</td>
                    <td>{{ $pv->email ?? '—' }}</td>
                    <td>{{ $pv->telefono ?? '—' }}</td>
                    <td>{{ $pv->ciudad ?? '—' }}</td>
                    <td><span class="badge {{ $pv->activo ? 'badge-success' : 'badge-danger' }}">{{ $pv->activo ? 'Activo' : 'Inactivo' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('proveedores.edit',$pv) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('proveedores.destroy',$pv) }}" onsubmit="return confirm('¿Desactivar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-ban"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">No hay proveedores registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $proveedores->withQueryString()->links() }}
</div>
@endsection

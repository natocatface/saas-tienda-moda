@extends('layouts.app')
@section('title','Clientes')
@section('page-title','Clientes')

@section('content')
<div class="page-header">
    <div>
        <h1>Clientes</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Clientes</div>
    </div>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Nuevo Cliente</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;align-items:flex-end;">
        <div class="form-group" style="margin-bottom:0;flex:1;">
            <label class="form-label">Buscar cliente</label>
            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Nombre, DNI, correo...">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Código</th><th>Cliente</th><th>DNI</th><th>Contacto</th><th>Ciudad</th><th>Puntos</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                <tr>
                    <td><code style="background:#f3f4f6;padding:3px 8px;border-radius:5px;font-size:11px;">{{ $c->codigo }}</code></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#e8398c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;">
                                {{ strtoupper(substr($c->nombre,0,1).substr($c->apellido,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;">{{ $c->nombre }} {{ $c->apellido }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $c->email ?? 'Sin correo' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $c->dni ?? '—' }}</td>
                    <td>{{ $c->telefono ?? '—' }}</td>
                    <td>{{ $c->ciudad ?? '—' }}</td>
                    <td>
                        <span style="background:linear-gradient(135deg,#fce7f3,#fbcfe8);color:#be185d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                            <i class="fa-solid fa-star"></i> {{ number_format($c->puntos,0) }}
                        </span>
                    </td>
                    <td><span class="badge {{ $c->activo ? 'badge-success' : 'badge-danger' }}">{{ $c->activo ? 'Activo' : 'Inactivo' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('clientes.show',$c) }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('clientes.edit',$c) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">No hay clientes registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $clientes->withQueryString()->links() }}
</div>
@endsection

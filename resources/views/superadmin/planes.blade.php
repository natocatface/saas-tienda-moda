@extends('superadmin.layout')
@section('title', 'Planes')

@section('content')
<div class="pagetitle">Planes de suscripción</div>
<div class="pagesub">Crea y administra los planes disponibles para las tiendas</div>

<style>
    .planes-grid { display:grid; grid-template-columns:360px 1fr; gap:22px; align-items:start; }
    .pl-form { background:#1e293b; border:1px solid #334155; border-radius:16px; padding:24px; position:sticky; top:90px; }
    .pl-form h3 { font-size:15px; color:#fff; margin-bottom:18px; }
    .fg { margin-bottom:14px; }
    .fg label { display:block; font-size:12px; color:#94a3b8; margin-bottom:6px; font-weight:500; }
    .fg input, .fg textarea { width:100%; background:#0f172a; border:1px solid #334155; color:#e2e8f0;
        padding:10px 13px; border-radius:9px; font-family:inherit; font-size:14px; }
    .fg input:focus, .fg textarea:focus { outline:none; border-color:#6366f1; }
    .fg .hint { font-size:11px; color:#64748b; margin-top:4px; }
    .g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
    .chk { display:flex; align-items:center; gap:8px; font-size:13px; color:#cbd5e1; margin:6px 0 16px; }
    .chk input { width:auto; }
    .btn-save { width:100%; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border:none;
        padding:12px; border-radius:10px; font-family:inherit; font-size:14px; font-weight:600; cursor:pointer; }
    .btn-save:hover { filter:brightness(1.1); }
    .btn-cancel { display:block; text-align:center; margin-top:10px; color:#94a3b8; font-size:13px; text-decoration:none; }

    .pl-cards { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:16px; }
    .pl-card { background:#1e293b; border:1px solid #334155; border-radius:16px; padding:22px; position:relative; }
    .pl-card.off { opacity:.55; }
    .pl-card .top { display:flex; justify-content:space-between; align-items:flex-start; }
    .pl-card .nm { font-size:17px; font-weight:700; color:#fff; }
    .pl-card .pr { font-size:28px; font-weight:700; color:#a5b4fc; margin:10px 0 2px; }
    .pl-card .pr small { font-size:12px; color:#64748b; font-weight:400; }
    .pl-card .ds { font-size:12px; color:#94a3b8; margin-bottom:14px; min-height:18px; }
    .pl-card ul { list-style:none; padding:0; margin:0 0 14px; }
    .pl-card li { font-size:12.5px; color:#cbd5e1; padding:4px 0; display:flex; gap:8px; }
    .pl-card li i { color:#34d399; margin-top:3px; }
    .lim { font-size:11px; color:#94a3b8; border-top:1px solid #273449; padding-top:10px; margin-bottom:14px; line-height:1.9; }
    .lim b { color:#e2e8f0; }
    .badge-on { font-size:10px; font-weight:700; padding:3px 9px; border-radius:20px; background:#064e3b; color:#6ee7b7; }
    .badge-off { font-size:10px; font-weight:700; padding:3px 9px; border-radius:20px; background:#334155; color:#94a3b8; }
    .pl-actions { display:flex; gap:6px; flex-wrap:wrap; }
    .mini { padding:6px 10px; border:none; border-radius:8px; font-family:inherit; font-size:11px; font-weight:600; cursor:pointer; color:#fff; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
    .m-edit { background:#4f46e5; } .m-toggle { background:#0d9488; } .m-del { background:#dc2626; }
    .tiendas-tag { font-size:11px; color:#64748b; margin-top:8px; }
    @media (max-width:900px){ .planes-grid{ grid-template-columns:1fr; } .pl-form{ position:static; } }
</style>

@php $caractTexto = $editar ? implode("\n", $editar->caracteristicas ?? []) : ''; @endphp

<div class="planes-grid">
    <!-- Formulario crear / editar -->
    <form class="pl-form" method="POST"
          action="{{ $editar ? route('admin.planes.update', $editar) : route('admin.planes.store') }}">
        @csrf
        <h3>
            <i class="fa-solid {{ $editar ? 'fa-pen' : 'fa-plus' }}" style="color:#818cf8;margin-right:6px;"></i>
            {{ $editar ? 'Editar plan' : 'Nuevo plan' }}
        </h3>

        <div class="fg">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $editar->nombre ?? '') }}" placeholder="Ej. Pro" required>
        </div>
        <div class="fg">
            <label>Precio mensual (S/)</label>
            <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $editar->precio ?? '') }}" placeholder="99.00" required>
        </div>

        <div class="g3">
            <div class="fg">
                <label>Productos</label>
                <input type="number" name="max_productos" value="{{ old('max_productos', $editar->max_productos ?? -1) }}" required>
            </div>
            <div class="fg">
                <label>Usuarios</label>
                <input type="number" name="max_usuarios" value="{{ old('max_usuarios', $editar->max_usuarios ?? -1) }}" required>
            </div>
            <div class="fg">
                <label>Ventas/mes</label>
                <input type="number" name="max_ventas_mes" value="{{ old('max_ventas_mes', $editar->max_ventas_mes ?? -1) }}" required>
            </div>
        </div>
        <div class="hint" style="font-size:11px;color:#64748b;margin:-6px 0 14px;">Usa <b>-1</b> para indicar ilimitado.</div>

        <div class="fg">
            <label>Descripción corta</label>
            <input type="text" name="descripcion" value="{{ old('descripcion', $editar->descripcion ?? '') }}" placeholder="Para tiendas en crecimiento">
        </div>
        <div class="fg">
            <label>Características (una por línea)</label>
            <textarea name="caracteristicas" rows="4" placeholder="Soporte prioritario&#10;Reportes avanzados">{{ old('caracteristicas', $caractTexto) }}</textarea>
        </div>

        <div class="chk">
            <input type="checkbox" name="activo" value="1" id="activo" {{ old('activo', $editar->activo ?? true) ? 'checked' : '' }}>
            <label for="activo" style="margin:0;">Plan activo (visible en el registro)</label>
        </div>

        <button type="submit" class="btn-save">
            <i class="fa-solid fa-floppy-disk"></i> {{ $editar ? 'Guardar cambios' : 'Crear plan' }}
        </button>
        @if($editar)
            <a href="{{ route('admin.planes') }}" class="btn-cancel">Cancelar edición</a>
        @endif
    </form>

    <!-- Lista de planes -->
    <div class="pl-cards">
        @forelse($planes as $plan)
            <div class="pl-card {{ $plan->activo ? '' : 'off' }}">
                <div class="top">
                    <div class="nm">{{ $plan->nombre }}</div>
                    <span class="{{ $plan->activo ? 'badge-on' : 'badge-off' }}">{{ $plan->activo ? 'Activo' : 'Inactivo' }}</span>
                </div>
                <div class="pr">S/ {{ number_format($plan->precio, 0) }}<small>/mes</small></div>
                <div class="ds">{{ $plan->descripcion }}</div>

                <ul>
                    @foreach(($plan->caracteristicas ?? []) as $c)
                        <li><i class="fa-solid fa-check"></i> {{ $c }}</li>
                    @endforeach
                </ul>

                <div class="lim">
                    Productos: <b>{{ $plan->max_productos == -1 ? 'Ilimitados' : $plan->max_productos }}</b><br>
                    Usuarios: <b>{{ $plan->max_usuarios == -1 ? 'Ilimitados' : $plan->max_usuarios }}</b><br>
                    Ventas/mes: <b>{{ $plan->max_ventas_mes == -1 ? 'Ilimitadas' : $plan->max_ventas_mes }}</b>
                </div>

                <div class="pl-actions">
                    <a href="{{ route('admin.planes', ['edit' => $plan->id]) }}" class="mini m-edit"><i class="fa-solid fa-pen"></i> Editar</a>
                    <form method="POST" action="{{ route('admin.planes.toggle', $plan) }}">
                        @csrf
                        <button class="mini m-toggle"><i class="fa-solid fa-power-off"></i> {{ $plan->activo ? 'Desactivar' : 'Activar' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.planes.destroy', $plan) }}" onsubmit="return confirm('¿Eliminar este plan?');">
                        @csrf
                        <button class="mini m-del"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
                <div class="tiendas-tag"><i class="fa-solid fa-store"></i> {{ $plan->tiendas_count }} tienda(s)</div>
            </div>
        @empty
            <div style="color:#64748b;">Aún no hay planes. Crea el primero con el formulario.</div>
        @endforelse
    </div>
</div>
@endsection

@extends('superadmin.layout')
@section('title', 'Mi Perfil')

@section('content')
<div class="pagetitle">Mi Perfil</div>
<div class="pagesub">Administra tu cuenta de super administrador</div>

<style>
    .perfil-grid { display:grid; grid-template-columns:340px 1fr; gap:22px; align-items:start; }
    .pf-card { background:#1e293b; border:1px solid #334155; border-radius:16px; overflow:hidden; }
    .pf-hero { background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:30px 24px; text-align:center; position:relative; }
    .pf-hero .av { width:88px; height:88px; border-radius:50%; margin:0 auto 14px;
        background:#fff; color:#4f46e5; display:flex; align-items:center; justify-content:center;
        font-size:32px; font-weight:700; box-shadow:0 8px 20px rgba(0,0,0,.3); }
    .pf-hero .nm { color:#fff; font-size:19px; font-weight:700; }
    .pf-hero .rl { display:inline-block; margin-top:8px; font-size:11px; font-weight:600;
        background:rgba(255,255,255,.2); color:#fff; padding:4px 12px; border-radius:20px; }
    .pf-body { padding:20px 24px; }
    .pf-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #273449; font-size:13px; }
    .pf-row:last-child { border-bottom:none; }
    .pf-row i { width:30px; height:30px; border-radius:8px; background:#0f172a; color:#818cf8;
        display:flex; align-items:center; justify-content:center; }
    .pf-row .k { color:#64748b; font-size:11px; text-transform:uppercase; }
    .pf-row .v { color:#e2e8f0; font-weight:500; }
    .pf-stats { display:grid; grid-template-columns:1fr 1fr; gap:10px; padding:0 24px 22px; }
    .pf-stat { background:#0f172a; border-radius:10px; padding:12px; text-align:center; }
    .pf-stat .n { font-size:20px; font-weight:700; color:#fff; }
    .pf-stat .l { font-size:10px; color:#64748b; text-transform:uppercase; margin-top:2px; }

    .form-panel { background:#1e293b; border:1px solid #334155; border-radius:16px; padding:24px 26px; margin-bottom:22px; }
    .form-panel h3 { font-size:15px; color:#fff; margin-bottom:4px; }
    .form-panel .desc { font-size:12px; color:#64748b; margin-bottom:20px; }
    .fg { margin-bottom:16px; }
    .fg label { display:block; font-size:12px; color:#94a3b8; margin-bottom:6px; font-weight:500; }
    .fg input { width:100%; background:#0f172a; border:1px solid #334155; color:#e2e8f0;
        padding:11px 14px; border-radius:10px; font-family:inherit; font-size:14px; transition:border-color .2s; }
    .fg input:focus { outline:none; border-color:#6366f1; }
    .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .btn-save { background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border:none;
        padding:12px 22px; border-radius:10px; font-family:inherit; font-size:14px; font-weight:600;
        cursor:pointer; transition:transform .15s; }
    .btn-save:hover { transform:translateY(-2px); }
    @media (max-width:900px){ .perfil-grid{ grid-template-columns:1fr; } .grid2{ grid-template-columns:1fr; } }
</style>

<div class="perfil-grid">
    <!-- Resumen -->
    <div class="pf-card">
        <div class="pf-hero">
            <div class="av">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div class="nm">{{ $user->name }}</div>
            <div class="rl"><i class="fa-solid fa-shield-halved"></i> Super Administrador</div>
        </div>
        <div class="pf-body">
            <div class="pf-row">
                <i class="fa-solid fa-envelope"></i>
                <div><div class="k">Correo</div><div class="v">{{ $user->email }}</div></div>
            </div>
            <div class="pf-row">
                <i class="fa-solid fa-phone"></i>
                <div><div class="k">Teléfono</div><div class="v">{{ $user->telefono ?: 'No registrado' }}</div></div>
            </div>
            <div class="pf-row">
                <i class="fa-solid fa-calendar-check"></i>
                <div><div class="k">Miembro desde</div><div class="v">{{ $user->created_at->locale('es')->isoFormat('D [de] MMMM YYYY') }}</div></div>
            </div>
        </div>
        <div class="pf-stats">
            <div class="pf-stat"><div class="n">{{ $stats['tiendas'] }}</div><div class="l">Tiendas</div></div>
            <div class="pf-stat"><div class="n">{{ $stats['activas'] }}</div><div class="l">Activas</div></div>
            <div class="pf-stat"><div class="n">{{ $stats['usuarios'] }}</div><div class="l">Usuarios</div></div>
            <div class="pf-stat"><div class="n">{{ $stats['planes'] }}</div><div class="l">Planes</div></div>
        </div>
    </div>

    <!-- Formularios -->
    <div>
        <div class="form-panel">
            <h3><i class="fa-solid fa-id-card" style="color:#818cf8;margin-right:6px;"></i> Datos personales</h3>
            <div class="desc">Actualiza tu nombre, correo y teléfono de contacto.</div>
            <form method="POST" action="{{ route('admin.perfil.update') }}">
                @csrf
                <div class="fg">
                    <label>Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="grid2">
                    <div class="fg">
                        <label>Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="fg">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" placeholder="Opcional">
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
            </form>
        </div>

        <div class="form-panel">
            <h3><i class="fa-solid fa-lock" style="color:#818cf8;margin-right:6px;"></i> Cambiar contraseña</h3>
            <div class="desc">Te recomendamos usar una contraseña segura y única.</div>
            <form method="POST" action="{{ route('admin.perfil.password') }}">
                @csrf
                <div class="fg">
                    <label>Contraseña actual</label>
                    <input type="password" name="password_actual" required>
                </div>
                <div class="grid2">
                    <div class="fg">
                        <label>Nueva contraseña</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="fg">
                        <label>Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fa-solid fa-key"></i> Actualizar contraseña</button>
            </form>
        </div>
    </div>
</div>
@endsection

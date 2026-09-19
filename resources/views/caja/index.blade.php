@extends('layouts.app')
@section('title','Caja')
@section('page-title','Caja')

@push('styles')
<style>
.caja-grid { display:grid; grid-template-columns: 1fr 360px; gap:20px; align-items:start; }
.kpi-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:18px; }
.kpi { background:#fff; border:1px solid var(--border); border-radius:12px; padding:14px 16px; }
.kpi .lbl { font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.4px; }
.kpi .val { font-size:20px; font-weight:700; color:var(--text-dark); margin-top:4px; }
.kpi.accent .val { color:var(--accent); }
.kpi.green .val { color:#16a34a; }
.kpi.red .val { color:#dc2626; }
.mov-row { display:flex; align-items:center; justify-content:space-between; padding:9px 0; border-bottom:1px solid var(--border); font-size:13px; }
.mov-row:last-child { border-bottom:none; }
.mov-in { color:#16a34a; font-weight:700; }
.mov-out { color:#dc2626; font-weight:700; }
@media(max-width:900px){ .caja-grid{grid-template-columns:1fr;} .kpi-row{grid-template-columns:1fr 1fr;} }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Caja</h1>
        <div class="breadcrumb"><a href="{{ route('dashboard') }}">Inicio</a><span class="breadcrumb-sep">›</span>Caja</div>
    </div>
</div>

@if($cajaAbierta)
    @php
        $ventasEf = $cajaAbierta->ventasEfectivo();
        $ingresos = $cajaAbierta->ingresosManuales();
        $egresos  = $cajaAbierta->egresosManuales();
        $esperado = $cajaAbierta->efectivoEsperado();
    @endphp

    <div class="kpi-row">
        <div class="kpi"><div class="lbl">Monto inicial</div><div class="val">S/ {{ number_format($cajaAbierta->monto_inicial,2) }}</div></div>
        <div class="kpi green"><div class="lbl">Ventas efectivo</div><div class="val">S/ {{ number_format($ventasEf,2) }}</div></div>
        <div class="kpi red"><div class="lbl">Egresos</div><div class="val">S/ {{ number_format($egresos,2) }}</div></div>
        <div class="kpi accent"><div class="lbl">Efectivo esperado</div><div class="val">S/ {{ number_format($esperado,2) }}</div></div>
    </div>

    <div class="caja-grid">
        <!-- Movimientos -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-right-left" style="color:var(--accent);margin-right:6px;"></i>Movimientos de caja</div>
                <span class="badge badge-success">Abierta · {{ $cajaAbierta->apertura?->format('d/m/Y H:i') }}</span>
            </div>

            <form method="POST" action="{{ route('caja.movimiento') }}" style="display:grid;grid-template-columns:130px 1fr 130px auto;gap:10px;margin-bottom:16px;">
                @csrf
                <select name="tipo" class="form-control" required>
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
                <input type="text" name="concepto" class="form-control" placeholder="Concepto (ej. pago a proveedor)" required>
                <input type="number" name="monto" step="0.01" min="0.01" class="form-control" placeholder="Monto" required>
                <button class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
            </form>

            <div class="table-wrap">
                @forelse($cajaAbierta->movimientos as $m)
                    <div class="mov-row">
                        <div>
                            <span class="{{ $m->tipo === 'ingreso' ? 'mov-in' : 'mov-out' }}">
                                <i class="fa-solid {{ $m->tipo === 'ingreso' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                {{ ucfirst($m->tipo) }}
                            </span>
                            <span style="color:var(--text-muted);margin-left:8px;">{{ $m->concepto }}</span>
                        </div>
                        <div class="{{ $m->tipo === 'ingreso' ? 'mov-in' : 'mov-out' }}">
                            {{ $m->tipo === 'ingreso' ? '+' : '−' }} S/ {{ number_format($m->monto,2) }}
                        </div>
                    </div>
                @empty
                    <p style="text-align:center;color:var(--text-muted);padding:24px;font-size:13px;">Sin movimientos manuales registrados.</p>
                @endforelse
            </div>
        </div>

        <!-- Cierre -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-lock" style="color:var(--accent);margin-right:6px;"></i>Cerrar caja</div></div>
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:14px;">Cuenta el efectivo físico en caja e ingrésalo para hacer el arqueo.</p>
            <form method="POST" action="{{ route('caja.cerrar', $cajaAbierta) }}" onsubmit="return confirm('¿Cerrar la caja? Esta acción no se puede deshacer.');">
                @csrf
                <div class="form-group">
                    <label class="form-label">Efectivo contado (S/)</label>
                    <input type="number" name="monto_contado" step="0.01" min="0" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" placeholder="Opcional">
                </div>
                <button class="btn btn-danger" style="width:100%;"><i class="fa-solid fa-lock"></i> Cerrar caja</button>
            </form>
            <div style="margin-top:14px;font-size:12px;color:var(--text-muted);line-height:1.8;">
                Inicial: <b>S/ {{ number_format($cajaAbierta->monto_inicial,2) }}</b><br>
                + Ventas efectivo: <b>S/ {{ number_format($ventasEf,2) }}</b><br>
                + Ingresos: <b>S/ {{ number_format($ingresos,2) }}</b><br>
                − Egresos: <b>S/ {{ number_format($egresos,2) }}</b><br>
                = Esperado: <b style="color:var(--accent);">S/ {{ number_format($esperado,2) }}</b>
            </div>
        </div>
    </div>
@else
    <div class="caja-grid">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-cash-register" style="color:var(--accent);margin-right:6px;"></i>No hay caja abierta</div></div>
            <p style="color:var(--text-muted);font-size:13px;">Abre la caja al iniciar el día para registrar ventas, ingresos y egresos en efectivo. Al final del turno ciérrala para hacer el arqueo.</p>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-unlock" style="color:var(--accent);margin-right:6px;"></i>Abrir caja</div></div>
            <form method="POST" action="{{ route('caja.abrir') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Monto inicial (S/)</label>
                    <input type="number" name="monto_inicial" step="0.01" min="0" class="form-control" placeholder="0.00" value="{{ old('monto_inicial', 0) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" placeholder="Opcional">
                </div>
                <button class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-unlock"></i> Abrir caja</button>
            </form>
        </div>
    </div>
@endif

<!-- Historial -->
<div class="card" style="margin-top:20px;">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent);margin-right:6px;"></i>Historial de cierres</div></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Fecha</th><th>Responsable</th><th>Inicial</th><th>Ventas</th><th>Egresos</th><th>Final</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse($historial as $c)
                <tr>
                    <td>{{ $c->fecha?->format('d/m/Y') }}</td>
                    <td>{{ $c->usuario->name ?? '—' }}</td>
                    <td>S/ {{ number_format($c->monto_inicial,2) }}</td>
                    <td>S/ {{ number_format($c->total_ventas,2) }}</td>
                    <td>S/ {{ number_format($c->total_egresos,2) }}</td>
                    <td style="font-weight:600;">S/ {{ number_format($c->monto_final,2) }}</td>
                    <td><span class="badge badge-danger">Cerrada</span></td>
                    <td><a href="{{ route('caja.show', $c) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:36px;color:var(--text-muted);">Aún no hay cierres de caja registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($historial->hasPages())<div style="margin-top:14px;">{{ $historial->links() }}</div>@endif
</div>
@endsection

@extends('layouts.app')
@section('title','Pago de Suscripción')
@section('page-title','Pago de Suscripción')

@section('content')
<div class="page-header">
    <div>
        <h1>Suscribirse a {{ $plan->nombre }}</h1>
        <div class="breadcrumb"><a href="{{ route('suscripcion.index') }}">Suscripción</a><span class="breadcrumb-sep">›</span>Pago</div>
    </div>
    <a href="{{ route('suscripcion.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
</div>

<form method="POST" action="{{ route('suscripcion.pagar') }}">
    @csrf
    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
    <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:20px;align-items:start;" class="ck-grid">

        <!-- Datos de pago -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-credit-card" style="color:var(--accent);margin-right:6px;"></i>Datos de pago</div></div>

            <div class="form-group">
                <label class="form-label">Período de suscripción</label>
                <select name="periodo_meses" id="periodo" class="form-control" onchange="recalcular()">
                    @foreach($periodos as $m => $txt)
                        <option value="{{ $m }}" data-meses="{{ $m }}">{{ $txt }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Método de pago</label>
                <select name="metodo" id="metodo" class="form-control" onchange="toggleTarjeta()">
                    <option value="tarjeta">Tarjeta de crédito/débito</option>
                    <option value="yape">Yape</option>
                    <option value="transferencia">Transferencia bancaria</option>
                </select>
            </div>

            <div id="tarjetaBox">
                <div class="form-group">
                    <label class="form-label">Número de tarjeta</label>
                    <input type="text" class="form-control" placeholder="4111 1111 1111 1111" maxlength="19" inputmode="numeric">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Vencimiento</label><input type="text" class="form-control" placeholder="MM/AA" maxlength="5"></div>
                    <div class="form-group"><label class="form-label">CVV</label><input type="text" class="form-control" placeholder="123" maxlength="4" inputmode="numeric"></div>
                </div>
                <div class="form-group"><label class="form-label">Titular</label><input type="text" class="form-control" placeholder="Nombre como aparece en la tarjeta"></div>
            </div>

            <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12px;color:#854d0e;">
                <i class="fa-solid fa-circle-info"></i> Entorno de demostración: el cobro se simula y se aprueba automáticamente. Para producción se conecta una pasarela real (Culqi, MercadoPago, Stripe). No ingreses datos reales de tarjeta.
            </div>
        </div>

        <!-- Resumen -->
        <div class="card">
            <div class="card-header"><div class="card-title">Resumen</div></div>
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                <span>Plan</span><span style="font-weight:700;">{{ $plan->nombre }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;color:var(--text-muted);">
                <span>Precio mensual</span><span>S/ {{ number_format($plan->precio,2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;color:var(--text-muted);">
                <span>Meses</span><span id="rMeses">1</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:800;border-top:1px solid var(--border);padding-top:12px;margin-top:8px;">
                <span>Total</span><span style="color:var(--accent);">S/ <span id="rTotal">{{ number_format($plan->precio,2) }}</span></span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:18px;padding:13px;font-size:15px;">
                <i class="fa-solid fa-lock"></i> Pagar ahora
            </button>
            <p style="text-align:center;font-size:11px;color:var(--text-muted);margin-top:10px;">Pago seguro · {{ $tienda->nombre }}</p>
        </div>
    </div>
</form>

@push('scripts')
<script>
const PRECIO = {{ (float) $plan->precio }};
function recalcular() {
    const meses = parseInt(document.getElementById('periodo').value, 10) || 1;
    document.getElementById('rMeses').textContent = meses;
    document.getElementById('rTotal').textContent = (PRECIO * meses).toFixed(2);
}
function toggleTarjeta() {
    const m = document.getElementById('metodo').value;
    document.getElementById('tarjetaBox').style.display = (m === 'tarjeta') ? '' : 'none';
}
recalcular();
</script>
@endpush
<style>@media(max-width:768px){.ck-grid{grid-template-columns:1fr !important;}}</style>
@endsection

@php $t = $pago->tienda; @endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Recibo {{ $pago->numero }}</title>
<style>
    * { box-sizing:border-box; margin:0; padding:0; font-family:'Segoe UI',Arial,sans-serif; }
    body { background:#e9ecf1; color:#1f2937; padding:26px; }
    .toolbar { max-width:560px; margin:0 auto 14px; display:flex; gap:10px; }
    .toolbar button { background:#e8398c; color:#fff; border:none; padding:10px 16px; border-radius:8px; font-weight:600; cursor:pointer; }
    .toolbar a { background:#fff; border:1px solid #ccc; color:#444; padding:10px 16px; border-radius:8px; text-decoration:none; }
    .recibo { max-width:560px; margin:0 auto; background:#fff; border-radius:14px; padding:34px; box-shadow:0 8px 30px rgba(0,0,0,.12); }
    header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #e8398c; padding-bottom:16px; margin-bottom:18px; }
    .brand { font-size:20px; font-weight:800; color:#7b1fa2; }
    .muted { color:#6b7280; font-size:13px; }
    .pill { display:inline-block; background:#dcfce7; color:#166534; font-weight:700; font-size:12px; padding:3px 12px; border-radius:12px; }
    .row { display:flex; justify-content:space-between; padding:7px 0; font-size:14px; border-bottom:1px dashed #e5e7eb; }
    .total { display:flex; justify-content:space-between; font-size:22px; font-weight:800; margin-top:14px; }
    .total span:last-child { color:#e8398c; }
    .foot { text-align:center; color:#9ca3af; font-size:12px; margin-top:20px; }
    @media print { body { background:#fff; padding:0; } .toolbar { display:none; } .recibo { box-shadow:none; } @page { margin:14mm; } }
</style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨 Imprimir / PDF</button>
        <a href="{{ route('suscripcion.index') }}">Volver</a>
    </div>

    <div class="recibo">
        <header>
            <div>
                <div class="brand">SaaS Tienda Moda</div>
                <div class="muted">Recibo de suscripción</div>
            </div>
            <div style="text-align:right;">
                <div style="font-weight:700;">{{ $pago->numero }}</div>
                <div class="muted">{{ $pago->created_at->format('d/m/Y H:i') }}</div>
                <span class="pill">{{ ucfirst($pago->estado) }}</span>
            </div>
        </header>

        <div class="row"><span class="muted">Tienda</span><span>{{ $t->nombre ?? '—' }}</span></div>
        <div class="row"><span class="muted">Plan</span><span>{{ $pago->plan->nombre ?? '—' }}</span></div>
        <div class="row"><span class="muted">Período</span><span>{{ $pago->periodo_meses }} mes(es)</span></div>
        <div class="row"><span class="muted">Vigencia</span><span>{{ $pago->periodo_inicio?->format('d/m/Y') }} — {{ $pago->periodo_fin?->format('d/m/Y') }}</span></div>
        <div class="row"><span class="muted">Método</span><span>{{ ucfirst($pago->metodo) }}</span></div>
        <div class="row"><span class="muted">Referencia</span><span>{{ $pago->referencia }}</span></div>

        <div class="total"><span>Total pagado</span><span>S/ {{ number_format($pago->monto,2) }}</span></div>

        <div class="foot">Gracias por tu suscripción · Documento generado automáticamente</div>
    </div>

    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 400));</script>
</body>
</html>

@php
    $t   = $venta->tienda;
    $sim = $t->simbolo_moneda ?? 'S/';
    $docNombre = match($venta->tipo_comprobante) {
        'factura' => 'FACTURA',
        'ticket'  => 'TICKET DE VENTA',
        default   => 'BOLETA DE VENTA',
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $docNombre }} {{ $venta->serie }}-{{ $venta->correlativo }}</title>
<style>
    * { box-sizing:border-box; margin:0; padding:0; }
    body { background:#e9ecf1; font-family:'Segoe UI',Arial,sans-serif; color:#111; padding:24px; }
    .toolbar { max-width:320px; margin:0 auto 16px; display:flex; gap:10px; }
    .toolbar button, .toolbar a {
        flex:1; text-align:center; padding:10px; border-radius:8px; border:none; cursor:pointer;
        font-size:13px; font-weight:600; text-decoration:none; font-family:inherit;
    }
    .btn-print { background:#e8398c; color:#fff; }
    .btn-back  { background:#fff; color:#444; border:1px solid #ccc; }
    .ticket {
        width:320px; margin:0 auto; background:#fff; padding:20px 22px;
        box-shadow:0 6px 24px rgba(0,0,0,.12); font-size:12px; line-height:1.55;
    }
    .center { text-align:center; }
    .tienda-nombre { font-size:16px; font-weight:700; letter-spacing:.5px; }
    .muted { color:#555; }
    .doc-box { border:1px solid #000; border-radius:6px; padding:6px; margin:10px 0; text-align:center; }
    .doc-box .tipo { font-weight:700; font-size:13px; }
    .doc-box .num  { font-weight:700; font-size:14px; }
    hr { border:none; border-top:1px dashed #999; margin:10px 0; }
    .row { display:flex; justify-content:space-between; }
    table { width:100%; border-collapse:collapse; margin:6px 0; }
    th { text-align:left; font-size:11px; border-bottom:1px solid #000; padding:3px 0; }
    td { font-size:11px; padding:3px 0; vertical-align:top; }
    td.r, th.r { text-align:right; }
    .tot { font-size:15px; font-weight:700; }
    .foot { text-align:center; margin-top:12px; font-size:11px; }
    @media print {
        body { background:#fff; padding:0; }
        .toolbar { display:none; }
        .ticket { box-shadow:none; width:80mm; padding:4mm; }
        @page { margin:4mm; }
    }
</style>
</head>
<body>
    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">🖨 Imprimir / PDF</button>
        <a class="btn-back" href="{{ route('ventas.show', $venta) }}">Volver</a>
    </div>

    <div class="ticket">
        <div class="center">
            <div class="tienda-nombre">{{ $t->nombre ?? 'Mi Tienda' }}</div>
            @if($t?->ruc)<div class="muted">RUC: {{ $t->ruc }}</div>@endif
            @if($t?->direccion)<div class="muted">{{ $t->direccion }}</div>@endif
            @if($t?->telefono)<div class="muted">Tel: {{ $t->telefono }}</div>@endif
        </div>

        <div class="doc-box">
            <div class="tipo">{{ $docNombre }}</div>
            <div class="num">{{ $venta->serie }}-{{ $venta->correlativo }}</div>
        </div>

        <div class="row"><span class="muted">Fecha:</span><span>{{ $venta->fecha->format('d/m/Y') }} {{ $venta->created_at->format('H:i') }}</span></div>
        <div class="row"><span class="muted">Atendió:</span><span>{{ $venta->vendedor->name ?? '—' }}</span></div>
        <div class="row"><span class="muted">Cliente:</span><span>{{ $venta->cliente ? $venta->cliente->nombre.' '.$venta->cliente->apellido : 'Cliente directo' }}</span></div>
        @if($venta->cliente?->dni)<div class="row"><span class="muted">DNI:</span><span>{{ $venta->cliente->dni }}</span></div>@endif

        <hr>
        <table>
            <thead><tr><th>Cant</th><th>Descripción</th><th class="r">Importe</th></tr></thead>
            <tbody>
                @foreach($venta->detalles as $d)
                <tr>
                    <td>{{ $d->cantidad }}</td>
                    <td>{{ $d->producto_nombre }}@if($d->talla || $d->color)<br><span class="muted">{{ trim($d->talla.' '.$d->color) }}</span>@endif<br><span class="muted">{{ $sim }} {{ number_format($d->precio_unitario,2) }} c/u</span></td>
                    <td class="r">{{ $sim }} {{ number_format($d->subtotal,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>

        <div class="row"><span class="muted">Subtotal</span><span>{{ $sim }} {{ number_format($venta->subtotal,2) }}</span></div>
        <div class="row"><span class="muted">IGV (18%)</span><span>{{ $sim }} {{ number_format($venta->igv,2) }}</span></div>
        @if($venta->descuento > 0)<div class="row"><span class="muted">Descuento</span><span>-{{ $sim }} {{ number_format($venta->descuento,2) }}</span></div>@endif
        <div class="row tot" style="margin-top:6px;"><span>TOTAL</span><span>{{ $sim }} {{ number_format($venta->total,2) }}</span></div>

        <hr>
        <div class="row"><span class="muted">Pago ({{ ucfirst($venta->metodo_pago) }})</span><span>{{ $sim }} {{ number_format($venta->monto_pagado,2) }}</span></div>
        @if($venta->vuelto > 0)<div class="row"><span class="muted">Vuelto</span><span>{{ $sim }} {{ number_format($venta->vuelto,2) }}</span></div>@endif

        @if($venta->estado === 'anulada')
            <div class="center" style="margin-top:10px;color:#dc2626;font-weight:700;border:1px solid #dc2626;padding:4px;">*** ANULADA ***</div>
        @endif

        <div class="foot">
            ¡Gracias por su compra!<br>
            <span class="muted">{{ $venta->numero_venta }}</span>
        </div>
    </div>

    <script>
        // Abre el diálogo de impresión automáticamente al cargar
        window.addEventListener('load', () => setTimeout(() => window.print(), 350));
    </script>
</body>
</html>

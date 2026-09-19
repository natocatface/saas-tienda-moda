<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $factura->tipoLabel() }} {{ $factura->numeroComprobante() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background:#eef0f6; color:#1e2139; padding:24px; }
        .hoja { max-width:760px; margin:0 auto; background:#fff; border:1px solid #e4e6f0; border-radius:10px; padding:34px 38px; }
        .top { display:flex; justify-content:space-between; gap:20px; align-items:flex-start; }
        .emisor .rz { font-size:20px; font-weight:700; }
        .emisor .li { font-size:13px; color:#6b7280; margin-top:2px; }
        .doc-box { border:2px solid #1e2139; border-radius:10px; padding:14px 18px; text-align:center; min-width:230px; }
        .doc-box .ruc { font-size:13px; font-weight:600; }
        .doc-box .tipo { font-size:13px; font-weight:600; margin:6px 0; text-transform:uppercase; }
        .doc-box .num { font-size:18px; font-weight:800; letter-spacing:1px; }
        .sep { height:1px; background:#e4e6f0; margin:22px 0; }
        .datos { display:grid; grid-template-columns:1fr 1fr; gap:6px 24px; font-size:13px; }
        .datos b { color:#374151; }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        thead th { text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; border-bottom:2px solid #e4e6f0; padding:8px 6px; }
        tbody td { font-size:13px; padding:8px 6px; border-bottom:1px solid #f0f1f6; }
        .r { text-align:right; }
        .tot { margin-top:16px; margin-left:auto; width:280px; font-size:13.5px; }
        .tot .row { display:flex; justify-content:space-between; padding:3px 0; }
        .tot .total { font-size:17px; font-weight:800; border-top:2px solid #1e2139; margin-top:6px; padding-top:8px; }
        .leyenda { margin-top:14px; font-size:12.5px; font-weight:600; }
        .foot { display:flex; justify-content:space-between; align-items:flex-end; gap:24px; margin-top:26px; }
        .qr-wrap { text-align:center; }
        .qr-wrap #qr { display:inline-block; }
        .qr-wrap .hash { font-size:10px; color:#9ca3af; margin-top:6px; word-break:break-all; max-width:150px; }
        .nota { font-size:11px; color:#6b7280; max-width:380px; line-height:1.6; }
        .acciones { max-width:760px; margin:0 auto 16px; display:flex; gap:10px; }
        .btn { font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; padding:9px 18px; border-radius:8px; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
        .btn-print { background:#e8398c; color:#fff; }
        .btn-back { background:#e5e7eb; color:#374151; }
        @media print {
            body { background:#fff; padding:0; }
            .hoja { border:none; border-radius:0; max-width:100%; padding:10px 6px; }
            .acciones { display:none; }
        }
    </style>
</head>
<body>
    <div class="acciones">
        <button class="btn btn-print" onclick="window.print()">Imprimir / Guardar PDF</button>
        <a class="btn btn-back" href="{{ route('ventas.show', $venta) }}">Volver</a>
    </div>

    <div class="hoja">
        <div class="top">
            <div class="emisor">
                <div class="rz">{{ $emisorRazon }}</div>
                <div class="li">{{ $venta->tienda->direccion ?? 'Lima - Perú' }}</div>
            </div>
            <div class="doc-box">
                <div class="ruc">RUC {{ $emisorRuc }}</div>
                <div class="tipo">{{ $factura->tipoLabel() }} Electrónica</div>
                <div class="num">{{ $factura->numeroComprobante() }}</div>
            </div>
        </div>

        <div class="sep"></div>

        <div class="datos">
            <div><b>Cliente:</b> {{ $venta->cliente ? trim($venta->cliente->nombre.' '.$venta->cliente->apellido) : 'Cliente varios' }}</div>
            <div><b>Fecha de emisión:</b> {{ optional($venta->fecha)->format('d/m/Y') }}</div>
            <div><b>Documento:</b> {{ $venta->cliente->dni ?? '-' }}</div>
            <div><b>Moneda:</b> Soles (PEN)</div>
        </div>

        <table>
            <thead>
                <tr><th>Descripción</th><th class="r">Cant.</th><th class="r">V. Unit.</th><th class="r">Importe</th></tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $d)
                <tr>
                    <td>{{ $d->producto_nombre }}</td>
                    <td class="r">{{ rtrim(rtrim(number_format($d->cantidad,2),'0'),'.') }}</td>
                    <td class="r">{{ number_format($d->precio_unitario,2) }}</td>
                    <td class="r">{{ number_format($d->precio_unitario * $d->cantidad,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php $sim = $venta->tienda->simbolo_moneda ?? 'S/'; @endphp
        <div class="tot">
            <div class="row"><span>Op. Gravada</span><span>{{ $sim }} {{ number_format($venta->subtotal,2) }}</span></div>
            <div class="row"><span>IGV (18%)</span><span>{{ $sim }} {{ number_format($venta->igv,2) }}</span></div>
            <div class="row total"><span>TOTAL</span><span>{{ $sim }} {{ number_format($venta->total,2) }}</span></div>
        </div>

        <div class="leyenda">{{ $leyenda }}</div>

        <div class="foot">
            <div class="nota">
                Representación impresa del comprobante electrónico.
                @if($factura->estado === 'ANULADO') <b style="color:#dc2626;">— DOCUMENTO ANULADO</b>@endif
                <br>Consulte su comprobante en el portal de SUNAT.
            </div>
            <div class="qr-wrap">
                <div id="qr"></div>
                @if($factura->id_fiscal)<div class="hash">Hash: {{ $factura->id_fiscal }}</div>@endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const qrData = @json($factura->qr);
        if (qrData) {
            new QRCode(document.getElementById('qr'), { text: qrData, width: 130, height: 130, correctLevel: QRCode.CorrectLevel.M });
        } else {
            document.getElementById('qr').innerHTML = '<span style="font-size:11px;color:#9ca3af;">Sin QR (comprobante no aceptado)</span>';
        }
    </script>
</body>
</html>

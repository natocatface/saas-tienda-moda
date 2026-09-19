@extends('layouts.app')
@section('title','Comprobante de Venta')
@section('page-title','Comprobante de Venta')

@section('content')
<div class="page-header">
    <div><h1>{{ $venta->numero_venta }}</h1></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <a href="{{ route('ventas.imprimir', $venta) }}" target="_blank" class="btn btn-outline"><i class="fa-solid fa-print"></i> Imprimir comprobante</a>
    </div>
</div>

<div style="max-width:700px;">
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;">
            <div>
                <div style="font-size:20px;font-weight:700;color:var(--accent);">{{ $venta->numero_venta }}</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $venta->fecha->format('d/m/Y') }}</div>
            </div>
            <span class="badge {{ $venta->estado=='completada'?'badge-success':($venta->estado=='anulada'?'badge-danger':'badge-warning') }}" style="font-size:14px;padding:6px 16px;">
                {{ ucfirst($venta->estado) }}
            </span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Cliente</div>
                <div style="font-weight:600;">{{ $venta->cliente ? $venta->cliente->nombre.' '.$venta->cliente->apellido : 'Cliente directo' }}</div>
                @if($venta->cliente)<div style="font-size:13px;color:var(--text-muted);">{{ $venta->cliente->dni ?? '' }}</div>@endif
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Vendedor</div>
                <div style="font-weight:600;">{{ $venta->vendedor->name }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Comprobante</div>
                <div>{{ ucfirst($venta->tipo_comprobante) }}@if($venta->serie) · {{ $venta->serie }}-{{ $venta->correlativo }}@endif</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px;">Método de Pago</div>
                <div>{{ ucfirst($venta->metodo_pago) }}</div>
            </div>
        </div>

        <table style="margin-bottom:20px;">
            <thead><tr><th>Producto</th><th>Cant.</th><th>Precio Unit.</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($venta->detalles as $d)
                <tr>
                    <td>
                        <div style="font-weight:500;">{{ $d->producto_nombre }}</div>
                        @if($d->talla || $d->color)<div style="font-size:11px;color:var(--text-muted);">{{ $d->talla }} {{ $d->color }}</div>@endif
                    </td>
                    <td>{{ $d->cantidad }}</td>
                    <td>S/ {{ number_format($d->precio_unitario,2) }}</td>
                    <td><strong>S/ {{ number_format($d->subtotal,2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="border-top:2px solid var(--border);padding-top:16px;max-width:280px;margin-left:auto;">
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:var(--text-muted);">
                <span>Subtotal</span><span>S/ {{ number_format($venta->subtotal,2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:var(--text-muted);">
                <span>IGV (18%)</span><span>S/ {{ number_format($venta->igv,2) }}</span>
            </div>
            @if($venta->descuento > 0)
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:#16a34a;">
                <span>Descuento</span><span>-S/ {{ number_format($venta->descuento,2) }}</span>
            </div>
            @endif
            <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:var(--text-dark);margin-top:10px;border-top:1px solid var(--border);padding-top:10px;">
                <span>TOTAL</span><span style="color:var(--accent);">S/ {{ number_format($venta->total,2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-top:6px;color:var(--text-muted);">
                <span>Pagado</span><span>S/ {{ number_format($venta->monto_pagado,2) }}</span>
            </div>
            @if($venta->vuelto > 0)
            <div style="display:flex;justify-content:space-between;font-size:13px;color:#16a34a;">
                <span>Vuelto</span><span>S/ {{ number_format($venta->vuelto,2) }}</span>
            </div>
            @endif
        </div>
    </div>

    @if(config('facturacion.habilitado'))
    @php $fe = $venta->facturaElectronica; $nc = $venta->notaCreditoElectronica; @endphp
    <div class="card" style="margin-top:20px;">
        <div class="card-header">
            <div class="card-title"><i class="fa-solid fa-file-invoice-dollar" style="color:var(--accent);margin-right:6px;"></i>Facturación Electrónica</div>
        </div>
        @if($fe)
            @php [$txt, $fg, $bg] = $fe->badge(); @endphp
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="badge" style="background:{{ $bg }};color:{{ $fg }};">{{ $txt }}</span>
                <strong>{{ $fe->tipoLabel() }} {{ $fe->numeroComprobante() }}</strong>
                @if($fe->codigo)<span style="color:var(--text-muted);font-size:12px;">Código SUNAT: {{ $fe->codigo }}</span>@endif
            </div>
            @if($fe->mensaje)<p style="margin-top:10px;font-size:13px;color:var(--text-muted);">{{ $fe->mensaje }}</p>@endif

            <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                @if($fe->xml_path)<a href="{{ route('facturacion.xml', $fe) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-code"></i> XML</a>@endif
                @if($fe->cdr_path)<a href="{{ route('facturacion.cdr', $fe) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-file-zipper"></i> CDR</a>@endif
                @if($fe->qr)<a href="{{ route('facturacion.imprimir', $fe) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-solid fa-print"></i> Imprimir</a>@endif

                @if(!$fe->estaAceptado() && $fe->estado !== 'ANULADO')
                <form method="POST" action="{{ route('ventas.facturar', $venta) }}">@csrf
                    <button class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Reintentar envío</button>
                </form>
                @endif

                @if($fe->bajaPendiente())
                <form method="POST" action="{{ route('facturacion.consultar', $fe) }}">@csrf
                    <button class="btn btn-outline btn-sm"><i class="fa-solid fa-rotate"></i> Consultar estado de baja</button>
                </form>
                @endif
            </div>

            {{-- Acciones sobre un comprobante aceptado --}}
            @if($fe->estaAceptado())
            <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--border);">
                @if($nc)
                    @php [$ntxt, $nfg, $nbg] = $nc->badge(); @endphp
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="font-size:13px;color:var(--text-muted);">Nota de crédito:</span>
                        <span class="badge" style="background:{{ $nbg }};color:{{ $nfg }};">{{ $ntxt }}</span>
                        <strong>{{ $nc->numeroComprobante() }}</strong>
                        @if($nc->xml_path)<a href="{{ route('facturacion.xml', $nc) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-code"></i> XML</a>@endif
                        @if($nc->cdr_path)<a href="{{ route('facturacion.cdr', $nc) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-file-zipper"></i> CDR</a>@endif
                        @if($nc->qr)<a href="{{ route('facturacion.imprimir', $nc) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-solid fa-print"></i> Imprimir</a>@endif
                    </div>
                @else
                    <form method="POST" action="{{ route('ventas.nota_credito', $venta) }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        @csrf
                        <input type="text" name="motivo" class="form-control" style="max-width:280px;" placeholder="Motivo (ej. Anulación de la operación)">
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Emitir nota de crédito para anular este comprobante?')">
                            <i class="fa-solid fa-file-circle-minus"></i> Emitir Nota de Crédito
                        </button>
                        @if($fe->esFactura())
                        <button class="btn btn-secondary btn-sm" formaction="{{ route('ventas.comunicar_baja', $venta) }}"
                                onclick="return confirm('¿Comunicar la baja de esta factura ante SUNAT?')">
                            <i class="fa-solid fa-ban"></i> Comunicar baja
                        </button>
                        @endif
                    </form>
                @endif
            </div>
            @endif
        @else
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:14px;">Esta venta aún no ha sido enviada a SUNAT.</p>
            <form method="POST" action="{{ route('ventas.facturar', $venta) }}">@csrf
                <button class="btn btn-primary"><i class="fa-solid fa-paper-plane" style="margin-right:6px;"></i> Enviar a SUNAT</button>
            </form>
        @endif
    </div>
    @endif
</div>
@endsection

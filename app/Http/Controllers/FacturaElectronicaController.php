<?php

namespace App\Http\Controllers;

use App\Facturacion\ServicioFacturacion;
use App\Facturacion\Support\MontoEnLetras;
use App\Models\FacturaElectronica;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacturaElectronicaController extends Controller
{
    /** Emite (envía a SUNAT) el comprobante electrónico de una venta. */
    public function emitir(Venta $venta, ServicioFacturacion $servicio)
    {
        if (!config('facturacion.habilitado')) {
            return back()->with('error', 'La facturación electrónica está deshabilitada.');
        }
        if ($venta->estado === 'anulada') {
            return back()->with('error', 'No se puede facturar una venta anulada.');
        }

        try {
            $factura = $servicio->emitirDesdeVenta($venta);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al emitir: ' . $e->getMessage());
        }

        return $factura->estaAceptado()
            ? back()->with('success', "Comprobante {$factura->numeroComprobante()} aceptado por SUNAT.")
            : back()->with('error', "SUNAT respondió ({$factura->codigo}): {$factura->mensaje}");
    }

    /** Emite una nota de crédito que anula el comprobante de la venta. */
    public function notaCredito(Request $request, Venta $venta, ServicioFacturacion $servicio)
    {
        $motivo = trim($request->input('motivo', '')) ?: 'ANULACION DE LA OPERACION';

        try {
            $nc = $servicio->emitirNotaCreditoDesdeVenta($venta, mb_strtoupper($motivo));
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al emitir nota de crédito: ' . $e->getMessage());
        }

        return $nc->estaAceptado()
            ? back()->with('success', "Nota de crédito {$nc->numeroComprobante()} aceptada por SUNAT.")
            : back()->with('error', "SUNAT respondió ({$nc->codigo}): {$nc->mensaje}");
    }

    /** Comunica la baja (anulación) de una factura ante SUNAT. */
    public function anular(Request $request, Venta $venta, ServicioFacturacion $servicio)
    {
        $motivo = trim($request->input('motivo', '')) ?: 'ERROR EN LA OPERACION';

        try {
            $c = $servicio->anularDesdeVenta($venta, mb_strtoupper($motivo));
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al comunicar la baja: ' . $e->getMessage());
        }

        return $c->bajaPendiente()
            ? back()->with('success', 'Comunicación de baja enviada. Consulta el estado en unos minutos.')
            : back()->with('error', "No se pudo enviar la baja: {$c->mensaje}");
    }

    /** Consulta el estado de un ticket (baja) ante SUNAT. */
    public function consultarEstado(FacturaElectronica $factura, ServicioFacturacion $servicio)
    {
        try {
            $c = $servicio->consultarTicket($factura);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al consultar: ' . $e->getMessage());
        }

        return match ($c->estado) {
            'ANULADO'    => back()->with('success', 'La baja fue aceptada. El comprobante quedó anulado.'),
            'EN_PROCESO' => back()->with('success', 'Aún en proceso en SUNAT. Intenta de nuevo en unos minutos.'),
            default      => back()->with('error', "SUNAT respondió: {$c->mensaje}"),
        };
    }

    /** Representación impresa (imprimible / PDF por navegador) con QR de SUNAT. */
    public function imprimir(FacturaElectronica $factura)
    {
        $venta = $factura->venta()->with('detalles', 'cliente', 'tienda')->firstOrFail();
        $tienda = $venta->tienda;

        $row = \App\Models\ConfiguracionFacturacion::where('tienda_id', $tienda->id)->first();
        $demo = !$row || $row->modo === 'beta_demo';
        // El RUC del QR es la fuente de verdad de lo que se emitió.
        $emisorRuc   = $factura->qr ? explode('|', $factura->qr)[0] : config('facturacion.sunat.ruc');
        $emisorRazon = $demo ? 'EMPRESA DEMO SAC' : ($tienda->nombre ?? 'EMPRESA');

        $leyenda = MontoEnLetras::convertir((float) $venta->total);

        return view('facturacion.representacion', compact('factura', 'venta', 'emisorRuc', 'emisorRazon', 'leyenda'));
    }

    public function xml(FacturaElectronica $factura)
    {
        abort_unless($factura->xml_path && Storage::disk('local')->exists($factura->xml_path), 404);
        return Storage::disk('local')->download($factura->xml_path, $factura->numeroComprobante() . '.xml');
    }

    public function cdr(FacturaElectronica $factura)
    {
        abort_unless($factura->cdr_path && Storage::disk('local')->exists($factura->cdr_path), 404);
        return Storage::disk('local')->download($factura->cdr_path, 'R-' . $factura->numeroComprobante() . '.zip');
    }
}

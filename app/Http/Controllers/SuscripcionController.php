<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    /** Resumen de la suscripción: plan actual, planes disponibles e historial de pagos. */
    public function index()
    {
        $tienda = Auth::user()->tienda;
        abort_unless($tienda, 404);
        $tienda->load('plan');

        $planes = Plan::where('activo', true)->orderBy('precio')->get();
        $pagos  = Pago::with('plan')->orderByDesc('id')->limit(20)->get();

        return view('suscripcion.index', compact('tienda', 'planes', 'pagos'));
    }

    /** Formulario de pago para un plan elegido. */
    public function checkout(Plan $plan)
    {
        $tienda = Auth::user()->tienda;
        abort_unless($tienda, 404);

        $periodos = [1 => '1 mes', 3 => '3 meses', 6 => '6 meses', 12 => '12 meses'];

        return view('suscripcion.checkout', compact('tienda', 'plan', 'periodos'));
    }

    /**
     * Procesa el pago de la suscripción.
     *
     * NOTA DE INTEGRACIÓN: aquí va la llamada a la pasarela de pago real
     * (Culqi / MercadoPago / Stripe). Hoy se simula una aprobación inmediata.
     * Para conectar la pasarela: cobrar el token de la tarjeta, y solo si la
     * respuesta es exitosa continuar con el registro y la activación.
     */
    public function pagar(Request $request)
    {
        $tienda = Auth::user()->tienda;
        abort_unless($tienda, 404);

        $data = $request->validate([
            'plan_id'       => 'required|exists:planes,id',
            'periodo_meses' => 'required|integer|in:1,3,6,12',
            'metodo'        => 'required|in:tarjeta,yape,transferencia',
        ]);

        $plan  = Plan::findOrFail($data['plan_id']);
        $meses = (int) $data['periodo_meses'];
        $monto = round((float) $plan->precio * $meses, 2);

        // ---- Punto de integración con la pasarela de pago real ----
        // $resultado = PasarelaPago::cobrar($request->token, $monto, ...);
        // if (!$resultado->ok) { return back()->with('error', 'El pago fue rechazado.'); }
        $referencia = 'SIM-' . strtoupper(uniqid());   // referencia simulada
        // -----------------------------------------------------------

        DB::transaction(function () use ($tienda, $plan, $meses, $monto, $data, $referencia) {
            // Base de extensión: si la suscripción sigue vigente, se acumula.
            $base = $tienda->fecha_vencimiento && $tienda->fecha_vencimiento->isFuture()
                ? $tienda->fecha_vencimiento->copy()
                : Carbon::today();

            $inicio = Carbon::today();
            $fin    = $base->addMonths($meses);

            $numero = 'PAGO-' . $tienda->id . '-' . date('Y') . '-' .
                str_pad(Pago::count() + 1, 5, '0', STR_PAD_LEFT);

            Pago::create([
                'plan_id'        => $plan->id,
                'user_id'        => Auth::id(),
                'numero'         => $numero,
                'monto'          => $monto,
                'periodo_meses'  => $meses,
                'metodo'         => $data['metodo'],
                'referencia'     => $referencia,
                'estado'         => 'pagado',
                'periodo_inicio' => $inicio,
                'periodo_fin'    => $fin,
            ]);

            // Activar / renovar la tienda con el plan pagado.
            $tienda->update([
                'plan_id'           => $plan->id,
                'estado'            => 'activa',
                'fecha_inicio'      => $tienda->fecha_inicio ?? $inicio,
                'fecha_vencimiento' => $fin,
            ]);
        });

        return redirect()->route('suscripcion.index')
            ->with('success', "Pago aprobado. Tu plan {$plan->nombre} está activo por {$meses} mes(es).");
    }

    /** Recibo de un pago. */
    public function recibo(Pago $pago)
    {
        $pago->load('plan', 'usuario', 'tienda');
        return view('suscripcion.recibo', compact('pago'));
    }
}

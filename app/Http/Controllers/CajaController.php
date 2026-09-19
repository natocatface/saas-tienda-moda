<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->latest('id')->first();

        if ($cajaAbierta) {
            $cajaAbierta->load(['movimientos' => fn ($q) => $q->latest('id'), 'usuario']);
        }

        $historial = Caja::with('usuario')
            ->where('estado', 'cerrada')
            ->orderByDesc('fecha')->orderByDesc('id')
            ->paginate(10);

        return view('caja.index', compact('cajaAbierta', 'historial'));
    }

    public function abrir(Request $request)
    {
        if (Caja::where('estado', 'abierta')->exists()) {
            return back()->with('error', 'Ya existe una caja abierta. Ciérrala antes de abrir otra.');
        }

        $data = $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ]);

        Caja::create([
            'user_id'       => Auth::id(),
            'fecha'         => Carbon::today(),
            'monto_inicial' => $data['monto_inicial'],
            'total_ventas'  => 0,
            'total_egresos' => 0,
            'estado'        => 'abierta',
            'apertura'      => now(),
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        return back()->with('success', 'Caja abierta correctamente.');
    }

    public function movimiento(Request $request)
    {
        $caja = Caja::where('estado', 'abierta')->latest('id')->first();
        if (!$caja) {
            return back()->with('error', 'No hay una caja abierta para registrar movimientos.');
        }

        $data = $request->validate([
            'tipo'     => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:200',
            'monto'    => 'required|numeric|min:0.01',
        ]);

        MovimientoCaja::create([
            'caja_id'  => $caja->id,
            'user_id'  => Auth::id(),
            'tipo'     => $data['tipo'],
            'concepto' => $data['concepto'],
            'monto'    => $data['monto'],
        ]);

        $tipo = $data['tipo'] === 'ingreso' ? 'Ingreso' : 'Egreso';
        return back()->with('success', "{$tipo} registrado en caja.");
    }

    public function cerrar(Request $request, Caja $caja)
    {
        abort_unless($caja->estaAbierta(), 400, 'La caja ya está cerrada.');

        $data = $request->validate([
            'monto_contado' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $esperado   = $caja->efectivoEsperado();
        $diferencia = round($data['monto_contado'] - $esperado, 2);

        $nota = 'Cierre: esperado S/ ' . number_format($esperado, 2)
            . ' | contado S/ ' . number_format($data['monto_contado'], 2)
            . ' | diferencia S/ ' . number_format($diferencia, 2);
        if (!empty($data['observaciones'])) {
            $nota .= ' — ' . $data['observaciones'];
        }

        $caja->update([
            'estado'        => 'cerrada',
            'cierre'        => now(),
            'monto_final'   => $data['monto_contado'],
            'total_ventas'  => $caja->ventasTotales(),
            'total_egresos' => $caja->egresosManuales(),
            'observaciones' => $nota,
        ]);

        return redirect()->route('caja.index')->with('success', 'Caja cerrada. ' . $nota);
    }

    public function show(Caja $caja)
    {
        $caja->load(['movimientos' => fn ($q) => $q->latest('id'), 'usuario']);
        return view('caja.show', compact('caja'));
    }
}

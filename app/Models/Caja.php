<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;

class Caja extends Model {
    use PerteneceTienda;

    protected $table = 'caja';

    protected $fillable = [
        'tienda_id', 'user_id', 'fecha', 'monto_inicial', 'monto_final',
        'total_ventas', 'total_egresos', 'estado', 'apertura', 'cierre', 'observaciones',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'apertura' => 'datetime',
        'cierre'   => 'datetime',
    ];

    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
    public function movimientos() { return $this->hasMany(MovimientoCaja::class); }

    public function estaAbierta(): bool { return $this->estado === 'abierta'; }

    /** Ventas en efectivo del día de esta caja (lo que debería estar físicamente). */
    public function ventasEfectivo(): float
    {
        return (float) Venta::where('estado', 'completada')
            ->where('metodo_pago', 'efectivo')
            ->whereDate('fecha', $this->fecha)
            ->sum('total');
    }

    /** Total de todas las ventas completadas del día (cualquier método). */
    public function ventasTotales(): float
    {
        return (float) Venta::where('estado', 'completada')
            ->whereDate('fecha', $this->fecha)
            ->sum('total');
    }

    public function ingresosManuales(): float
    {
        return (float) $this->movimientos()->where('tipo', 'ingreso')->sum('monto');
    }

    public function egresosManuales(): float
    {
        return (float) $this->movimientos()->where('tipo', 'egreso')->sum('monto');
    }

    /** Efectivo esperado en caja al cierre. */
    public function efectivoEsperado(): float
    {
        return round(
            (float) $this->monto_inicial
            + $this->ventasEfectivo()
            + $this->ingresosManuales()
            - $this->egresosManuales(),
            2
        );
    }
}

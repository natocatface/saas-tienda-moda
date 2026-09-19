<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tienda extends Model
{
    protected $table = 'tiendas';

    protected $fillable = [
        'nombre', 'slug', 'ruc', 'email', 'telefono', 'direccion',
        'logo', 'plan_id', 'estado', 'fecha_inicio', 'fecha_vencimiento',
        'igv', 'moneda', 'simbolo_moneda', 'serie_boleta', 'serie_factura',
    ];

    protected $casts = [
        'fecha_inicio'      => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    // --- Estado de la suscripción -------------------------------------

    public function estaActiva(): bool
    {
        if ($this->estado === 'suspendida') {
            return false;
        }
        if ($this->fecha_vencimiento && $this->fecha_vencimiento->isPast()) {
            return false;
        }
        return true;
    }

    public function diasRestantes(): ?int
    {
        if (!$this->fecha_vencimiento) {
            return null;
        }
        return max(0, Carbon::today()->diffInDays($this->fecha_vencimiento, false));
    }

    public function estadoBadge(): array
    {
        return match ($this->estado) {
            'activa'     => ['Activa', '#16a34a', '#dcfce7'],
            'prueba'     => ['Prueba', '#c2410c', '#ffedd5'],
            'suspendida' => ['Suspendida', '#dc2626', '#fee2e2'],
            default      => ['—', '#6b7280', '#f3f4f6'],
        };
    }

    // --- Límites del plan ---------------------------------------------

    /**
     * ¿La tienda puede agregar un registro más del recurso indicado?
     * $recurso: 'productos' | 'usuarios' | 'ventas_mes'
     */
    public function puedeAgregar(string $recurso): bool
    {
        if (!$this->plan) {
            return true;
        }
        $map = [
            'productos'  => ['max_productos', fn () => Producto::where('tienda_id', $this->id)->count()],
            'usuarios'   => ['max_usuarios', fn () => User::where('tienda_id', $this->id)->count()],
            'ventas_mes' => ['max_ventas_mes', fn () => Venta::where('tienda_id', $this->id)
                                ->whereMonth('fecha', now()->month)
                                ->whereYear('fecha', now()->year)->count()],
        ];
        if (!isset($map[$recurso])) {
            return true;
        }
        [$campo, $contador] = $map[$recurso];
        $limite = (int) $this->plan->{$campo};
        if ($limite === -1) {
            return true; // ilimitado
        }
        return $contador() < $limite;
    }
}

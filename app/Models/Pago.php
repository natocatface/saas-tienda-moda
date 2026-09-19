<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;

class Pago extends Model {
    use PerteneceTienda;

    protected $table = 'pagos';

    protected $fillable = [
        'tienda_id', 'plan_id', 'user_id', 'numero', 'monto', 'periodo_meses',
        'metodo', 'referencia', 'estado', 'periodo_inicio', 'periodo_fin',
    ];

    protected $casts = [
        'monto'          => 'decimal:2',
        'periodo_inicio' => 'date',
        'periodo_fin'    => 'date',
    ];

    public function plan() { return $this->belongsTo(Plan::class); }
    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
}

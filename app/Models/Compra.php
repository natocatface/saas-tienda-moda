<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Compra extends Model {
    use PerteneceTienda;
    protected $table = 'compras';
    protected $fillable = ['tienda_id','numero_compra','proveedor_id','user_id','fecha','total','estado','observaciones'];
    protected $casts = ['fecha' => 'date'];
    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
    public function detalles() { return $this->hasMany(DetalleCompra::class); }
}

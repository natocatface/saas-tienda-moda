<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class DetalleVenta extends Model {
    use PerteneceTienda;
    protected $table = 'detalle_ventas';
    protected $fillable = ['tienda_id','venta_id','producto_id','variante_id','producto_nombre','talla','color','cantidad','precio_unitario','descuento','subtotal'];
    public function venta() { return $this->belongsTo(Venta::class); }
    public function producto() { return $this->belongsTo(Producto::class); }
}

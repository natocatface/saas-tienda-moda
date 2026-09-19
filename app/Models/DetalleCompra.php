<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class DetalleCompra extends Model {
    use PerteneceTienda;
    protected $table = 'detalle_compras';
    protected $fillable = ['tienda_id','compra_id','producto_id','variante_id','cantidad','precio_unitario','subtotal'];
    public function compra() { return $this->belongsTo(Compra::class); }
    public function producto() { return $this->belongsTo(Producto::class); }
    public function variante() { return $this->belongsTo(ProductoVariante::class, 'variante_id'); }
}

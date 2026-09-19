<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class ProductoVariante extends Model {
    use PerteneceTienda;
    protected $table = 'producto_variantes';
    protected $fillable = ['tienda_id','producto_id','talla','color','codigo_barra','stock','stock_minimo'];
    public function producto() { return $this->belongsTo(Producto::class); }
}

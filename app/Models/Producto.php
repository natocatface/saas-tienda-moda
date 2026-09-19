<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Producto extends Model {
    use PerteneceTienda;
    protected $fillable = ['tienda_id','categoria_id','subcategoria_id','marca_id','proveedor_id','codigo','nombre','descripcion','genero','tipo','precio_compra','precio_venta','precio_oferta','imagen','activo','destacado'];
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function subcategoria() { return $this->belongsTo(Subcategoria::class); }
    public function marca() { return $this->belongsTo(Marca::class); }
    public function proveedor() { return $this->belongsTo(Proveedor::class); }
    public function variantes() { return $this->hasMany(ProductoVariante::class); }
    public function stockTotal() { return $this->variantes()->sum('stock'); }
}

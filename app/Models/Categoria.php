<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Categoria extends Model {
    use PerteneceTienda;
    protected $fillable = ['tienda_id','nombre', 'slug', 'descripcion', 'imagen', 'genero', 'activo'];
    public function productos() { return $this->hasMany(Producto::class); }
    public function subcategorias() { return $this->hasMany(Subcategoria::class); }
}

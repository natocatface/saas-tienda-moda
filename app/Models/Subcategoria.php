<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;

class Subcategoria extends Model {
    use PerteneceTienda;
    protected $table = 'subcategorias';
    protected $fillable = ['tienda_id','categoria_id','nombre','slug','activo'];
    protected $casts = ['activo' => 'boolean'];
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function productos() { return $this->hasMany(Producto::class); }
}

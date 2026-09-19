<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Marca extends Model {
    use PerteneceTienda;
    protected $fillable = ['tienda_id','nombre', 'logo', 'activo'];
    public function productos() { return $this->hasMany(Producto::class); }
}

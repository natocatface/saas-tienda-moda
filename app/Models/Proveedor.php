<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Proveedor extends Model {
    use PerteneceTienda;
    protected $table = 'proveedores';
    protected $fillable = ['tienda_id','nombre','ruc','email','telefono','direccion','ciudad','pais','contacto','activo'];
    public function productos() { return $this->hasMany(Producto::class); }
}

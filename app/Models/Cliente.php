<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Cliente extends Model {
    use PerteneceTienda;
    protected $fillable = ['tienda_id','codigo','nombre','apellido','dni','email','telefono','direccion','ciudad','fecha_nacimiento','genero','puntos','activo'];
    public function ventas() { return $this->hasMany(Venta::class); }
    public function nombreCompleto() { return $this->nombre . ' ' . $this->apellido; }
}

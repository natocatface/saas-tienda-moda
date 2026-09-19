<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['rol_id', 'tienda_id', 'name', 'email', 'password', 'telefono', 'avatar', 'activo', 'es_super_admin'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed', 'es_super_admin' => 'boolean'];

    public function rol() { return $this->belongsTo(Rol::class); }
    public function tienda() { return $this->belongsTo(Tienda::class); }
    public function ventas() { return $this->hasMany(Venta::class); }
    public function isAdmin() { return $this->rol_id === 1; }
    public function esSuperAdmin() { return (bool) $this->es_super_admin; }

    /**
     * Módulos permitidos por rol. '*' = todos los módulos.
     * Las claves de módulo coinciden con las usadas en el middleware "modulo:".
     */
    public const MODULOS_POR_ROL = [
        'Administrador' => ['*'],
        'Vendedor'      => ['dashboard', 'ventas', 'clientes', 'caja', 'reportes_ventas'],
        'Almacén'       => ['dashboard', 'productos', 'categorias', 'marcas', 'stock', 'proveedores', 'compras', 'reportes_inventario'],
    ];

    /** ¿El usuario tiene acceso al módulo indicado? */
    public function puede(string $modulo): bool
    {
        // El super-admin de plataforma no usa estos módulos de tienda.
        if ($this->es_super_admin) {
            return true;
        }

        $rol = $this->rol->nombre ?? null;

        // Sin rol reconocido: por seguridad, solo el panel principal.
        $modulos = self::MODULOS_POR_ROL[$rol] ?? ['dashboard'];

        return in_array('*', $modulos, true) || in_array($modulo, $modulos, true);
    }
}

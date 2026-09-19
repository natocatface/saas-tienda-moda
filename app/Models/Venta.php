<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;
class Venta extends Model {
    use PerteneceTienda;
    protected $fillable = ['tienda_id','numero_venta','cliente_id','user_id','fecha','tipo_comprobante','serie','correlativo','subtotal','descuento','igv','total','metodo_pago','monto_pagado','vuelto','estado','observaciones'];
    protected $casts = ['fecha' => 'date'];
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function vendedor() { return $this->belongsTo(User::class, 'user_id'); }
    public function detalles() { return $this->hasMany(DetalleVenta::class); }
    public function facturaElectronica() { return $this->hasOne(FacturaElectronica::class)->whereIn('tipo', ['FACTURA', 'BOLETA']); }
    public function notaCreditoElectronica() { return $this->hasOne(FacturaElectronica::class)->where('tipo', 'NOTA_CREDITO')->latestOfMany(); }
}

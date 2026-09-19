<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceTienda;

class FacturaElectronica extends Model
{
    use PerteneceTienda;

    protected $table = 'facturas_electronicas';

    protected $fillable = [
        'tienda_id', 'venta_id', 'documento_afectado_id', 'pais', 'tipo', 'serie', 'correlativo',
        'estado', 'codigo', 'mensaje', 'motivo', 'id_fiscal', 'qr', 'ticket', 'xml_path', 'cdr_path',
    ];

    /** Código del catálogo 01 de SUNAT para este tipo de comprobante. */
    public function tipoDocSunat(): string
    {
        return match ($this->tipo) {
            'FACTURA'      => '01',
            'BOLETA'       => '03',
            'NOTA_CREDITO' => '07',
            'NOTA_DEBITO'  => '08',
            default        => '01',
        };
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function documentoAfectado()
    {
        return $this->belongsTo(FacturaElectronica::class, 'documento_afectado_id');
    }

    public function numeroComprobante(): string
    {
        return $this->serie . '-' . str_pad((string) $this->correlativo, 6, '0', STR_PAD_LEFT);
    }

    public function esNotaCredito(): bool
    {
        return $this->tipo === 'NOTA_CREDITO';
    }

    public function esFactura(): bool
    {
        return $this->tipo === 'FACTURA';
    }

    public function estaAceptado(): bool
    {
        return in_array($this->estado, ['ACEPTADO', 'OBSERVADO'], true);
    }

    /** ¿Tiene una comunicación de baja pendiente de consultar? */
    public function bajaPendiente(): bool
    {
        return $this->estado === 'EN_PROCESO' && !empty($this->ticket);
    }

    public function tipoLabel(): string
    {
        return match ($this->tipo) {
            'FACTURA'      => 'Factura',
            'BOLETA'       => 'Boleta',
            'NOTA_CREDITO' => 'Nota de crédito',
            default        => $this->tipo,
        };
    }

    /** @return array{0:string,1:string,2:string} [texto, color, fondo] */
    public function badge(): array
    {
        return match ($this->estado) {
            'ACEPTADO'   => ['Aceptado por SUNAT', '#16a34a', '#dcfce7'],
            'OBSERVADO'  => ['Aceptado con observaciones', '#c2410c', '#ffedd5'],
            'RECHAZADO'  => ['Rechazado', '#dc2626', '#fee2e2'],
            'ANULADO'    => ['Anulado', '#6b7280', '#f3f4f6'],
            'EN_PROCESO' => ['En proceso', '#2563eb', '#dbeafe'],
            default      => ['Error', '#dc2626', '#fee2e2'],
        };
    }
}

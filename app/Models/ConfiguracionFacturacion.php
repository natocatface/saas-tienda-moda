<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionFacturacion extends Model
{
    protected $table = 'configuracion_facturacion';

    protected $fillable = [
        'tienda_id', 'activo', 'modo', 'ruc', 'sol_usuario',
        'sol_clave', 'certificado_path', 'certificado_password',
    ];

    protected $casts = [
        'activo'               => 'boolean',
        // Cifrado en reposo: Laravel los guarda encriptados y los descifra al leer.
        'sol_clave'            => 'encrypted',
        'certificado_password' => 'encrypted',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function esDemo(): bool
    {
        return $this->modo === 'beta_demo';
    }

    public function tieneCertificado(): bool
    {
        return !empty($this->certificado_path);
    }

    public function modoLabel(): string
    {
        return match ($this->modo) {
            'beta_demo'  => 'Pruebas (demo SUNAT)',
            'beta'       => 'Pruebas (mi certificado)',
            'produccion' => 'Producción',
            default      => $this->modo,
        };
    }

    /** ¿La configuración está completa para poder emitir? */
    public function estaListo(): bool
    {
        if (!$this->activo) {
            return false;
        }
        if ($this->modo === 'beta_demo') {
            return true; // usa credenciales y certificado de prueba globales
        }
        return !empty($this->ruc)
            && !empty($this->sol_usuario)
            && !empty($this->sol_clave)
            && $this->tieneCertificado();
    }

    /**
     * Indicador de estado para la interfaz.
     * @return array{0:string,1:string,2:string,3:string} [texto, color, fondo, icono]
     */
    public function estadoConfig(): array
    {
        if (!$this->exists) {
            return ['Sin configurar', '#6b7280', '#f3f4f6', 'fa-circle-info'];
        }
        if (!$this->activo) {
            return ['Desactivada', '#6b7280', '#f3f4f6', 'fa-circle-pause'];
        }
        if ($this->estaListo()) {
            return ['Listo para emitir' . ($this->esDemo() ? ' (demo)' : ''), '#16a34a', '#dcfce7', 'fa-circle-check'];
        }
        return ['Faltan datos por completar', '#c2410c', '#ffedd5', 'fa-triangle-exclamation'];
    }
}

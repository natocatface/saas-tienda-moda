<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionFacturacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionFacturacionController extends Controller
{
    public function index()
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);

        $config = ConfiguracionFacturacion::firstOrNew(['tienda_id' => $tienda->id]);

        return view('facturacion.config', compact('config', 'tienda'));
    }

    public function update(Request $request)
    {
        $tienda = auth()->user()->tienda;
        abort_unless($tienda, 404);

        $data = $request->validate([
            'activo'               => 'nullable|boolean',
            'modo'                 => 'required|in:beta_demo,beta,produccion',
            'ruc'                  => 'nullable|string|size:11',
            'sol_usuario'          => 'nullable|string|max:60',
            'sol_clave'            => 'nullable|string|max:120',
            'certificado'          => 'nullable|file|max:5120', // .pem / .pfx / .p12
            'certificado_password' => 'nullable|string|max:120',
        ], [
            'ruc.size' => 'El RUC debe tener 11 dígitos.',
        ]);

        $config = ConfiguracionFacturacion::firstOrNew(['tienda_id' => $tienda->id]);
        $config->tienda_id  = $tienda->id;
        $config->activo     = $request->boolean('activo');
        $config->modo       = $data['modo'];
        $config->ruc        = $data['ruc'] ?? $config->ruc;
        $config->sol_usuario = $data['sol_usuario'] ?? $config->sol_usuario;

        // Solo actualiza la clave SOL si el usuario escribió una nueva.
        if (!empty($data['sol_clave'])) {
            $config->sol_clave = $data['sol_clave'];
        }
        if (!empty($data['certificado_password'])) {
            $config->certificado_password = $data['certificado_password'];
        }

        // Procesar certificado subido (para modos 'beta' y 'produccion').
        if ($request->hasFile('certificado')) {
            try {
                $config->certificado_path = $this->guardarCertificado(
                    $request->file('certificado'),
                    $tienda->id,
                    $data['certificado_password'] ?? ''
                );
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'No se pudo procesar el certificado: ' . $e->getMessage());
            }
        }

        // Validación de coherencia para producción.
        if ($config->modo === 'produccion') {
            if (empty($config->ruc) || empty($config->sol_usuario) || empty($config->certificado_path)) {
                return back()->withInput()->with('error',
                    'Para producción debes completar RUC, usuario SOL y subir tu certificado.');
            }
        }

        $config->save();

        return back()->with('success', 'Configuración de facturación guardada.');
    }

    /**
     * Guarda el certificado como PEM (cert + llave) en storage/app.
     * Acepta .pem directamente o convierte un .pfx/.p12 usando la contraseña.
     */
    private function guardarCertificado($archivo, int $tiendaId, string $password): string
    {
        $contenido = file_get_contents($archivo->getRealPath());
        $ext = strtolower($archivo->getClientOriginalExtension());

        if (in_array($ext, ['pfx', 'p12'], true)) {
            $certs = [];
            if (!openssl_pkcs12_read($contenido, $certs, $password)) {
                throw new \RuntimeException('Certificado .pfx inválido o contraseña incorrecta.');
            }
            $pem = ($certs['cert'] ?? '') . ($certs['pkey'] ?? '');
        } else {
            // Se asume PEM con certificado y llave privada.
            $pem = $contenido;
            if (!str_contains($pem, 'PRIVATE KEY')) {
                throw new \RuntimeException('El .pem debe incluir el certificado y la llave privada.');
            }
        }

        $ruta = "certificates/{$tiendaId}/certificate.pem";
        Storage::disk('local')->put($ruta, $pem);
        return $ruta;
    }
}

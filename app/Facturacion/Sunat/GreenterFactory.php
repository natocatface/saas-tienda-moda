<?php

namespace App\Facturacion\Sunat;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

/**
 * Construye y configura el objeto See de Greenter (firma + envío a SUNAT) a
 * partir de la configuración EFECTIVA de una tienda:
 *   ['modo','ruc','sol_usuario','sol_clave','certificado']
 *
 * La resolución de esa configuración (por tienda o demo) la hace
 * ServicioFacturacion; esta clase solo la aplica.
 */
class GreenterFactory
{
    public function __construct(private array $config) {}

    public function crear(): See
    {
        $see = new See();
        $see->setCertificate($this->cargarCertificado());
        $see->setService(($this->config['modo'] ?? 'beta_demo') === 'produccion'
            ? SunatEndpoints::FE_PRODUCCION
            : SunatEndpoints::FE_BETA);
        $see->setClaveSOL(
            $this->config['ruc'] ?? '20000000001',
            $this->config['sol_usuario'] ?? 'MODDATOS',
            $this->config['sol_clave'] ?? 'moddatos',
        );

        return $see;
    }

    private function cargarCertificado(): string
    {
        $ruta = $this->config['certificado'] ?? '';
        if (!$ruta || !is_file($ruta)) {
            throw new \RuntimeException(
                "Certificado no encontrado ({$ruta}). En modo demo coloca el certificado en " .
                "storage/app/certificates/certificate.pem; en modo real cárgalo en Configuración de Facturación."
            );
        }
        return file_get_contents($ruta);
    }
}

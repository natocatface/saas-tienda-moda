<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Peru;

/**
 * Firma digital XML-DSig del CPE con el certificado (.pfx/.pem) de la empresa
 * emisora. El certificado se carga desde el almacén seguro de credenciales
 * (credenciales_pais), nunca desde el código ni el repositorio.
 */
final class FirmadorXmlSunat
{
    public function firmar(string $xml, string $empresaId): string
    {
        // TODO: cargar certificado de la empresa (descifrado en memoria) y aplicar
        //       XML-DSig sobre el nodo ext:UBLExtensions/.../ds:Signature.
        //       Con Greenter: (new SignedXml($certificado))->signXml($xml).
        return $xml; // placeholder: en producción devuelve el XML firmado
    }
}

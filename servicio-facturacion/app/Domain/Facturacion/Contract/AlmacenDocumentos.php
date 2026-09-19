<?php

declare(strict_types=1);

namespace App\Domain\Facturacion\Contract;

use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;

/**
 * Puerto de almacenamiento de documentos (XML firmado, respuesta del organismo,
 * PDF). La implementación decide el destino (disco local, S3, GCS).
 */
interface AlmacenDocumentos
{
    public function guardarXmlFirmado(IdentificadorComprobante $id, string $contenidoXml): string;

    public function guardarRespuestaOrganismo(IdentificadorComprobante $id, string $contenido): string;

    public function guardarPdf(IdentificadorComprobante $id, string $contenidoPdf): string;

    /** Devuelve el contenido de un documento previamente guardado. */
    public function leer(string $ruta): string;
}

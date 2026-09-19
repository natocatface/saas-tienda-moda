<?php

declare(strict_types=1);

namespace App\Infrastructure\Almacenamiento;

use App\Domain\Facturacion\Contract\AlmacenDocumentos;
use App\Domain\Facturacion\ValueObject\IdentificadorComprobante;
use Illuminate\Support\Facades\Storage;

/**
 * Implementación del puerto AlmacenDocumentos sobre el sistema de archivos de
 * Laravel (disco 'local' o 's3' según config). Cambiar a S3 es cambiar el
 * disco, sin tocar el dominio ni los casos de uso.
 */
final class AlmacenDocumentosDisco implements AlmacenDocumentos
{
    public function __construct(private readonly string $disco = 'local') {}

    public function guardarXmlFirmado(IdentificadorComprobante $id, string $contenidoXml): string
    {
        return $this->guardar($id, 'firmado.xml', $contenidoXml);
    }

    public function guardarRespuestaOrganismo(IdentificadorComprobante $id, string $contenido): string
    {
        return $this->guardar($id, 'respuesta.xml', $contenido);
    }

    public function guardarPdf(IdentificadorComprobante $id, string $contenidoPdf): string
    {
        return $this->guardar($id, 'representacion.pdf', $contenidoPdf);
    }

    public function leer(string $ruta): string
    {
        return (string) Storage::disk($this->disco)->get($ruta);
    }

    private function guardar(IdentificadorComprobante $id, string $archivo, string $contenido): string
    {
        // Partición por comprobante; en producción añadir empresa/pais/año/mes (ver doc §13).
        $ruta = "comprobantes/{$id->valor()}/{$archivo}";
        Storage::disk($this->disco)->put($ruta, $contenido);
        return $ruta;
    }
}

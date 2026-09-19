<?php

declare(strict_types=1);

namespace App\Infrastructure\Proveedor\Peru;

use App\Domain\Facturacion\Exception\FacturacionException;

/**
 * Cliente del web service SOAP de SUNAT (o de un OSE/PSE). Encapsula endpoints,
 * credenciales SOL, empaquetado ZIP y descompresión del CDR.
 *
 * Todos los fallos de red/servicio se traducen a FacturacionException con
 * categoría COMUNICACION para que el orquestador los reintente.
 */
final class ClienteSoapSunat
{
    public function __construct(
        private readonly string $endpoint,
        private readonly bool $modoBeta = true,
    ) {}

    /** Envía un comprobante (factura/NC) y devuelve el CDR (XML) descomprimido. */
    public function enviarComprobante(string $xmlFirmado): string
    {
        try {
            // TODO: zip del XML -> sendBill(fileName, contentZip) -> unzip del applicationResponse (CDR).
            return '<ApplicationResponse>TODO-CDR</ApplicationResponse>';
        } catch (\Throwable $e) {
            throw new FacturacionException(
                'Error de comunicación con SUNAT: ' . $e->getMessage(),
                FacturacionException::CAT_COMUNICACION,
                'SUNAT_COMM',
            );
        }
    }

    /** Envía un resumen diario (boletas) y devuelve el número de ticket. */
    public function enviarResumenDiario(string $xmlFirmado): string
    {
        // TODO: sendSummary(...) -> ticket
        return 'TICKET-' . uniqid();
    }

    /** Envía una comunicación de baja y devuelve el ticket. */
    public function enviarComunicacionBaja(string $xmlFirmado): string
    {
        // TODO: sendSummary(...) para VoidedDocuments -> ticket
        return 'TICKET-BAJA-' . uniqid();
    }

    /** Consulta el estado de un ticket (getStatus) y devuelve el CDR. */
    public function consultarTicket(string $ticket): string
    {
        // TODO: getStatus(ticket) -> CDR
        return '<ApplicationResponse>TODO-CDR-TICKET</ApplicationResponse>';
    }
}

<?php

namespace App\Facturacion\Sunat;

use App\Facturacion\Dto\ComprobanteDto;
use App\Facturacion\Dto\NotaCreditoDto;
use App\Facturacion\Support\MontoEnLetras;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;

/**
 * Traduce los DTO de dominio a los modelos de Greenter (UBL 2.1):
 *   · invoice()  -> factura/boleta
 *   · note()     -> nota de crédito (07)
 *   · voided()   -> comunicación de baja (RA)
 * Aquí viven los mapeos a catálogos SUNAT (tipoDoc, IGV, afectación, leyendas).
 */
class ConstructorDocumento
{
    // ---------- FACTURA / BOLETA ----------

    public function invoice(ComprobanteDto $dto): Invoice
    {
        [$details, $gravadas, $igv, $total] = $this->detallesYTotales($dto);

        return (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($this->tipoSunat($dto->tipo))
            ->setSerie($dto->serie)
            ->setCorrelativo((string) $dto->correlativo)
            ->setFechaEmision($dto->fechaEmision ? new \DateTime($dto->fechaEmision) : new \DateTime())
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda($dto->moneda)
            ->setCompany($this->company($dto))
            ->setClient($this->client($dto))
            ->setMtoOperGravadas($gravadas)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setValorVenta($gravadas)
            ->setSubTotal($total)
            ->setMtoImpVenta($total)
            ->setDetails($details)
            ->setLegends([$this->legendMonto($total)]);
    }

    // ---------- NOTA DE CRÉDITO (07) ----------

    public function note(NotaCreditoDto $nc): Note
    {
        $dto = $nc->comprobante;
        [$details, $gravadas, $igv, $total] = $this->detallesYTotales($dto);

        return (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc('07')
            ->setSerie($dto->serie)
            ->setCorrelativo((string) $dto->correlativo)
            ->setFechaEmision($dto->fechaEmision ? new \DateTime($dto->fechaEmision) : new \DateTime())
            ->setTipDocAfectado($nc->tipoDocAfectado)
            ->setNumDocfectado($nc->numDocAfectado)
            ->setCodMotivo($nc->codMotivo)
            ->setDesMotivo($nc->desMotivo)
            ->setTipoMoneda($dto->moneda)
            ->setCompany($this->company($dto))
            ->setClient($this->client($dto))
            ->setMtoOperGravadas($gravadas)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setMtoImpVenta($total)
            ->setDetails($details)
            ->setLegends([$this->legendMonto($total)]);
    }

    // ---------- COMUNICACIÓN DE BAJA (RA) ----------

    public function voided(int $correlativo, string $serieAfectada, int $correlativoAfectado, string $tipoDocAfectado, string $motivo): Voided
    {
        $hoy = new \DateTime();
        $detalle = (new VoidedDetail())
            ->setTipoDoc($tipoDocAfectado)
            ->setSerie($serieAfectada)
            ->setCorrelativo((string) $correlativoAfectado)
            ->setDesMotivoBaja($motivo);

        return (new Voided())
            ->setCorrelativo((string) $correlativo)
            ->setFecGeneracion($hoy)      // fecha de emisión del documento a dar de baja
            ->setFecComunicacion($hoy)    // fecha de la comunicación (hoy)
            ->setCompany($this->companyDesdeConfig())
            ->setDetails([$detalle]);
    }

    // ---------- helpers ----------

    /** @return array{0:SaleDetail[],1:float,2:float,3:float} [details, gravadas, igv, total] */
    private function detallesYTotales(ComprobanteDto $dto): array
    {
        $igvFactor = $dto->tasaIgv;
        $porcentajeIgv = round($igvFactor * 100, 2);

        $details = [];
        $gravadas = 0.0;
        $igvTotal = 0.0;

        foreach ($dto->items as $item) {
            $valorUnitario = (float) $item['valor_unitario'];
            $cantidad = (float) $item['cantidad'];
            $valorVenta = round($valorUnitario * $cantidad, 2);
            $igvLinea = round($valorVenta * $igvFactor, 2);

            $gravadas += $valorVenta;
            $igvTotal += $igvLinea;

            $details[] = (new SaleDetail())
                ->setCodProducto($item['codigo'] ?? 'P001')
                ->setUnidad('NIU')
                ->setCantidad($cantidad)
                ->setDescripcion($item['descripcion'])
                ->setMtoBaseIgv($valorVenta)
                ->setPorcentajeIgv($porcentajeIgv)
                ->setIgv($igvLinea)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($igvLinea)
                ->setMtoValorUnitario($valorUnitario)
                ->setMtoValorVenta($valorVenta)
                ->setMtoPrecioUnitario(round($valorUnitario * (1 + $igvFactor), 2));
        }

        $gravadas = round($gravadas, 2);
        $igvTotal = round($igvTotal, 2);
        return [$details, $gravadas, $igvTotal, round($gravadas + $igvTotal, 2)];
    }

    private function legendMonto(float $total): Legend
    {
        return (new Legend())->setCode('1000')->setValue(MontoEnLetras::convertir($total));
    }

    private function company(ComprobanteDto $dto): Company
    {
        return (new Company())
            ->setRuc($dto->emisorRuc)
            ->setRazonSocial($dto->emisorRazonSocial)
            ->setNombreComercial($dto->emisorRazonSocial)
            ->setAddress($this->direccion($dto->emisorDireccion));
    }

    /** Emisor tomado de la configuración (para comunicación de baja). */
    private function companyDesdeConfig(): Company
    {
        $cfg = config('facturacion.sunat');
        return (new Company())
            ->setRuc($cfg['ruc'])
            ->setRazonSocial('EMPRESA DEMO SAC')
            ->setNombreComercial('EMPRESA DEMO')
            ->setAddress($this->direccion(null));
    }

    private function direccion(?string $direccion): Address
    {
        return (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion($direccion ?: 'AV. PRINCIPAL 123')
            ->setCodLocal('0000');
    }

    private function client(ComprobanteDto $dto): Client
    {
        return (new Client())
            ->setTipoDoc($dto->receptorTipoDoc)
            ->setNumDoc($dto->receptorNumDoc)
            ->setRznSocial($dto->receptorNombre);
    }

    private function tipoSunat(string $tipo): string
    {
        return match ($tipo) {
            'FACTURA'      => '01',
            'BOLETA'       => '03',
            'NOTA_CREDITO' => '07',
            'NOTA_DEBITO'  => '08',
            default        => '01',
        };
    }
}

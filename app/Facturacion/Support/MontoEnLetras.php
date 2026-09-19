<?php

namespace App\Facturacion\Support;

/**
 * Convierte un monto a su representación en letras (requerido por SUNAT en la
 * leyenda 1000). Ej: 118.00 -> "SON CIENTO DIECIOCHO CON 00/100 SOLES".
 */
class MontoEnLetras
{
    private const UNIDADES = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    private const ESPECIALES = [
        10 => 'DIEZ', 11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE',
        16 => 'DIECISEIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE', 20 => 'VEINTE',
    ];
    private const DECENAS = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    private const CENTENAS = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    public static function convertir(float $monto, string $moneda = 'SOLES'): string
    {
        $entero = (int) floor($monto);
        $decimal = (int) round(($monto - $entero) * 100);
        $letras = $entero === 0 ? 'CERO' : self::seccionMillones($entero);
        return sprintf('SON %s CON %02d/100 %s', trim($letras), $decimal, $moneda);
    }

    private static function seccionMillones(int $n): string
    {
        if ($n < 1000) {
            return self::centenas($n);
        }
        if ($n < 1000000) {
            $miles = intdiv($n, 1000);
            $resto = $n % 1000;
            $prefijo = $miles === 1 ? 'MIL' : self::centenas($miles) . ' MIL';
            return trim($prefijo . ' ' . self::centenas($resto));
        }
        $millones = intdiv($n, 1000000);
        $resto = $n % 1000000;
        $prefijo = $millones === 1 ? 'UN MILLON' : self::centenas($millones) . ' MILLONES';
        return trim($prefijo . ' ' . self::seccionMillones($resto));
    }

    private static function centenas(int $n): string
    {
        if ($n === 0) return '';
        if ($n === 100) return 'CIEN';
        $c = intdiv($n, 100);
        $resto = $n % 100;
        return trim(self::CENTENAS[$c] . ' ' . self::decenas($resto));
    }

    private static function decenas(int $n): string
    {
        if ($n === 0) return '';
        if ($n < 10) return self::UNIDADES[$n];
        if (isset(self::ESPECIALES[$n])) return self::ESPECIALES[$n];
        if ($n < 30) return 'VEINTI' . strtolower(self::UNIDADES[$n % 10]);
        $d = intdiv($n, 10);
        $u = $n % 10;
        return $u === 0 ? self::DECENAS[$d] : self::DECENAS[$d] . ' Y ' . self::UNIDADES[$u];
    }
}

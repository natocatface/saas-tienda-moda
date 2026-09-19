<?php

use App\Infrastructure\Proveedor\Peru\SunatProveedor;
use App\Infrastructure\Proveedor\Colombia\DianProveedor;
use App\Infrastructure\Proveedor\Chile\SiiProveedor;
use App\Infrastructure\Proveedor\Argentina\ArcaProveedor;
use App\Infrastructure\Proveedor\Mexico\SatProveedor;

return [

    /*
    |--------------------------------------------------------------------------
    | Mapa país -> adaptador
    |--------------------------------------------------------------------------
    | ESTE es el único punto que se amplía al agregar un país. Añadir un país
    | nuevo = crear su carpeta en Infrastructure/Proveedor/{Pais}/ y sumar una
    | línea aquí. No se modifica ninguna clase existente (Abierto/Cerrado).
    */
    'proveedores' => [
        'PE' => SunatProveedor::class,
        'CO' => DianProveedor::class,
        'CL' => SiiProveedor::class,
        'AR' => ArcaProveedor::class,
        'MX' => SatProveedor::class,
        // 'EC' => \App\Infrastructure\Proveedor\Ecuador\SriProveedor::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reintentos (cola)
    |--------------------------------------------------------------------------
    */
    'reintentos' => [
        'max' => 5,
        'backoff' => [0, 30, 120, 600, 3600], // segundos por intento
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración por país (endpoints, modo beta). Los SECRETOS (certificados,
    | usuario SOL, tokens PAC) NO van aquí: van cifrados en credenciales_pais.
    |--------------------------------------------------------------------------
    */
    'paises' => [
        'PE' => [
            'endpoint_factura' => env('SUNAT_ENDPOINT', 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService'),
            'modo_beta' => env('SUNAT_BETA', true),
        ],
        'AR' => [
            'endpoint' => env('ARCA_ENDPOINT', 'https://wswhomo.arca.gob.ar/wsfev1/service.asmx'),
        ],
        'MX' => [
            'pac' => env('PAC_MX', 'finkok'),
        ],
    ],

    'almacenamiento' => [
        'disco' => env('FACTURACION_DISCO', 'local'),
        'retencion_anios' => 5,
    ],
];

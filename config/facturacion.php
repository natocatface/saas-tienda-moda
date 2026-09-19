<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facturación electrónica (Fase 1: Perú / SUNAT con Greenter)
    |--------------------------------------------------------------------------
    */

    // País por defecto de emisión.
    'pais' => env('FACTURACION_PAIS', 'PE'),

    // ¿Mostrar el botón "Enviar a SUNAT" y permitir emitir? (apagar para ocultar)
    'habilitado' => env('FACTURACION_HABILITADO', true),

    // Emitir automáticamente al registrar la venta (true) o solo manual (false).
    'auto_emitir' => env('FACTURACION_AUTO', false),

    'sunat' => [
        // 'beta_demo'  -> ambiente beta con el certificado/credenciales DEMO de SUNAT
        // 'beta'       -> ambiente beta con TU certificado
        // 'produccion' -> ambiente real
        'modo' => env('SUNAT_MODO', 'beta_demo'),

        // Credenciales SOL. En 'beta_demo' son las públicas de prueba de SUNAT.
        'ruc'         => env('SUNAT_RUC', '20000000001'),
        'sol_usuario' => env('SUNAT_SOL_USER', 'MODDATOS'),
        'sol_clave'   => env('SUNAT_SOL_PASS', 'moddatos'),

        // Ruta al certificado en formato PEM (.pem). Ver docs/FASE1_SUNAT_SETUP.md
        'certificado' => env('SUNAT_CERT', storage_path('app/certificates/certificate.pem')),
    ],
];

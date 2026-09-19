<?php

use App\Http\Controllers\Api\V1\FacturaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API del Servicio de Facturación Electrónica (SFE) — v1
|--------------------------------------------------------------------------
| El ERP consume estas rutas. El contrato es de NEGOCIO, idéntico para todos
| los países. La autenticación por empresa se aplica con el middleware
| 'auth.empresa' (token/API-key por tenant).
*/

Route::prefix('api/v1')->middleware('auth.empresa')->group(function () {

    Route::post('/facturas', [FacturaController::class, 'emitir'])->name('facturas.emitir');
    Route::get('/comprobantes/{id}', [FacturaController::class, 'estado'])->name('comprobantes.estado');

    // Rutas previstas (controladores análogos):
    // Route::post('/notas-credito', [NotaCreditoController::class, 'emitir']);
    // Route::post('/comprobantes/{id}/anular', [EstadoController::class, 'anular']);
    // Route::get('/comprobantes/{id}/xml', [EstadoController::class, 'xml']);
    // Route::get('/comprobantes/{id}/pdf', [EstadoController::class, 'pdf']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\FacturaElectronicaController;
use App\Http\Controllers\ConfiguracionFacturacionController;

// --- Utilidad: cargar datos de demostración visitando esta URL (solo en local) ---
Route::get('/seed-demo', function () {
    if (!app()->environment('local')) {
        abort(403);
    }
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'DemoSeeder',
        '--force' => true,
    ]);
    return '<div style="font-family:sans-serif;text-align:center;padding:50px 20px;">'
        .'<h2 style="color:#16a34a;">&#10004; Plataforma SaaS lista</h2>'
        .'<p>Tienda demo + super admin + 10 registros por modulo.</p>'
        .'<div style="max-width:520px;margin:20px auto;text-align:left;background:#fdf2f8;border:1px solid #fbcfe8;padding:18px 22px;border-radius:12px;font-size:14px;line-height:1.9;">'
        .'<b>Admin de tienda:</b> admin@tiendamoda.com / admin123<br>'
        .'<b>Super admin:</b> superadmin@tiendamoda.com / super123'
        .'</div>'
        .'<p style="margin-top:10px;">'
        .'<a href="/login" style="color:#e8398c;font-weight:bold;margin:0 10px;">Iniciar sesion &rarr;</a>'
        .'<a href="/registro" style="color:#7b1fa2;font-weight:bold;margin:0 10px;">Registrar nueva tienda &rarr;</a>'
        .'</p>'
        .'<pre style="text-align:left;max-width:600px;margin:20px auto;background:#f3f4f6;padding:16px;border-radius:8px;font-size:12px;">'
        .htmlspecialchars(\Illuminate\Support\Facades\Artisan::output())
        .'</pre></div>';
});

// --- Utilidad: aplicar migraciones pendientes SIN borrar datos (solo local) ---
Route::get('/migrar', function () {
    if (!app()->environment('local')) {
        abort(403);
    }
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return '<div style="font-family:sans-serif;text-align:center;padding:50px;">'
        .'<h2 style="color:#16a34a;">&#10004; Migraciones aplicadas</h2>'
        .'<p><a href="/dashboard" style="color:#e8398c;font-weight:bold;">Ir al Dashboard &rarr;</a></p>'
        .'<pre style="text-align:left;max-width:600px;margin:20px auto;background:#f3f4f6;padding:16px;border-radius:8px;font-size:12px;">'
        .htmlspecialchars(\Illuminate\Support\Facades\Artisan::output())
        .'</pre></div>';
});

// Auth
Route::get('/',        [AuthController::class, 'showLogin'])->name('login');
Route::get('/login',   [AuthController::class, 'showLogin']);
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro de nueva tienda (onboarding SaaS)
Route::get('/registro',  [RegistroController::class, 'showForm'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

// Recuperación de contraseña
Route::get('/password/olvide',        [PasswordResetController::class, 'showLinkRequest'])->name('password.request');
Route::post('/password/olvide',       [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/password/reset',        [PasswordResetController::class, 'reset'])->name('password.update');

// Panel Super-Admin (dueño de la plataforma)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->group(function () {
    Route::get('/',        [SuperAdminController::class, 'index'])->name('admin.index');
    Route::get('/perfil',          [SuperAdminController::class, 'perfil'])->name('admin.perfil');
    Route::post('/perfil',         [SuperAdminController::class, 'actualizarPerfil'])->name('admin.perfil.update');
    Route::post('/perfil/password',[SuperAdminController::class, 'cambiarPassword'])->name('admin.perfil.password');
    Route::get('/tiendas', [SuperAdminController::class, 'tiendas'])->name('admin.tiendas');

    // Gestión de planes
    Route::get('/planes',                  [SuperAdminController::class, 'planes'])->name('admin.planes');
    Route::post('/planes',                 [SuperAdminController::class, 'guardarPlan'])->name('admin.planes.store');
    Route::post('/planes/{plan}',          [SuperAdminController::class, 'guardarPlan'])->name('admin.planes.update');
    Route::post('/planes/{plan}/toggle',   [SuperAdminController::class, 'togglePlan'])->name('admin.planes.toggle');
    Route::post('/planes/{plan}/eliminar', [SuperAdminController::class, 'eliminarPlan'])->name('admin.planes.destroy');
    Route::post('/tiendas/{tienda}/estado',   [SuperAdminController::class, 'cambiarEstado'])->name('admin.tiendas.estado');
    Route::post('/tiendas/{tienda}/plan',     [SuperAdminController::class, 'cambiarPlan'])->name('admin.tiendas.plan');
    Route::post('/tiendas/{tienda}/extender', [SuperAdminController::class, 'extender'])->name('admin.tiendas.extender');
});

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::resource('productos', ProductoController::class)->middleware('modulo:productos');

    // Clientes
    Route::resource('clientes', ClienteController::class)->middleware('modulo:clientes');

    // Categorías
    Route::middleware('modulo:categorias')->group(function () {
        Route::get('/categorias',                  [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias',                 [CategoriaController::class, 'store'])->name('categorias.store');
        Route::post('/categorias/{categoria}',     [CategoriaController::class, 'update'])->name('categorias.update');
        Route::post('/categorias/{categoria}/del', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });

    // Subcategorías
    Route::middleware('modulo:categorias')->group(function () {
        Route::get('/subcategorias',                        [SubcategoriaController::class, 'index'])->name('subcategorias.index');
        Route::post('/subcategorias',                       [SubcategoriaController::class, 'store'])->name('subcategorias.store');
        Route::post('/subcategorias/{subcategoria}',        [SubcategoriaController::class, 'update'])->name('subcategorias.update');
        Route::post('/subcategorias/{subcategoria}/del',    [SubcategoriaController::class, 'destroy'])->name('subcategorias.destroy');
    });

    // Marcas
    Route::middleware('modulo:marcas')->group(function () {
        Route::get('/marcas',              [MarcaController::class, 'index'])->name('marcas.index');
        Route::post('/marcas',             [MarcaController::class, 'store'])->name('marcas.store');
        Route::post('/marcas/{marca}',     [MarcaController::class, 'update'])->name('marcas.update');
        Route::post('/marcas/{marca}/del', [MarcaController::class, 'destroy'])->name('marcas.destroy');
    });

    // Stock / Inventario
    Route::middleware('modulo:stock')->group(function () {
        Route::get('/stock',              [StockController::class, 'index'])->name('stock.index');
        Route::post('/stock/{variante}',  [StockController::class, 'update'])->name('stock.update');
    });

    // Caja
    Route::middleware('modulo:caja')->group(function () {
        Route::get('/caja',                [CajaController::class, 'index'])->name('caja.index');
        Route::post('/caja/abrir',         [CajaController::class, 'abrir'])->name('caja.abrir');
        Route::post('/caja/movimiento',    [CajaController::class, 'movimiento'])->name('caja.movimiento');
        Route::post('/caja/{caja}/cerrar', [CajaController::class, 'cerrar'])->name('caja.cerrar');
        Route::get('/caja/{caja}',         [CajaController::class, 'show'])->name('caja.show');
    });

    // Compras
    Route::middleware('modulo:compras')->group(function () {
        Route::get('/compras',          [CompraController::class, 'index'])->name('compras.index');
        Route::get('/compras/crear',    [CompraController::class, 'create'])->name('compras.create');
        Route::post('/compras',         [CompraController::class, 'store'])->name('compras.store');
        Route::get('/compras/{compra}', [CompraController::class, 'show'])->name('compras.show');
        Route::post('/compras/{compra}/anular', [CompraController::class, 'anular'])->name('compras.anular');
    });

    // Usuarios (equipo de la tienda) — solo Administrador
    Route::middleware('modulo:usuarios')->group(function () {
        Route::get('/usuarios',                 [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios',                [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::post('/usuarios/{usuario}',      [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::post('/usuarios/{usuario}/toggle',[UsuarioController::class, 'toggle'])->name('usuarios.toggle');
    });

    // Suscripción y pagos — solo Administrador
    Route::middleware('modulo:suscripcion')->group(function () {
        Route::get('/suscripcion',                 [SuscripcionController::class, 'index'])->name('suscripcion.index');
        Route::get('/suscripcion/checkout/{plan}', [SuscripcionController::class, 'checkout'])->name('suscripcion.checkout');
        Route::post('/suscripcion/pagar',          [SuscripcionController::class, 'pagar'])->name('suscripcion.pagar');
        Route::get('/suscripcion/recibo/{pago}',   [SuscripcionController::class, 'recibo'])->name('suscripcion.recibo');
    });

    // Mi Tienda — solo Administrador
    Route::middleware('modulo:tienda')->group(function () {
        Route::get('/mi-tienda',  [TiendaController::class, 'edit'])->name('tienda.edit');
        Route::post('/mi-tienda', [TiendaController::class, 'update'])->name('tienda.update');
    });

    // Configuración — solo Administrador
    Route::middleware('modulo:configuracion')->group(function () {
        Route::get('/configuracion',  [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    });

    // Configuración de Facturación Electrónica (por empresa) — solo Administrador
    Route::middleware('modulo:facturacion')->group(function () {
        Route::get('/facturacion/configuracion',  [ConfiguracionFacturacionController::class, 'index'])->name('facturacion.config');
        Route::post('/facturacion/configuracion', [ConfiguracionFacturacionController::class, 'update'])->name('facturacion.config.update');
    });

    // Ventas
    Route::middleware('modulo:ventas')->group(function () {
        Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/ventas/{venta}/anular',   [VentaController::class, 'anular'])->name('ventas.anular');
        Route::get('/ventas/{venta}/imprimir',  [VentaController::class, 'imprimir'])->name('ventas.imprimir');

        // Facturación electrónica (SUNAT)
        Route::post('/ventas/{venta}/facturar',       [FacturaElectronicaController::class, 'emitir'])->name('ventas.facturar');
        Route::post('/ventas/{venta}/nota-credito',   [FacturaElectronicaController::class, 'notaCredito'])->name('ventas.nota_credito');
        Route::post('/ventas/{venta}/comunicar-baja', [FacturaElectronicaController::class, 'anular'])->name('ventas.comunicar_baja');
        Route::post('/facturacion/{factura}/consultar', [FacturaElectronicaController::class, 'consultarEstado'])->name('facturacion.consultar');
        Route::get('/facturacion/{factura}/imprimir', [FacturaElectronicaController::class, 'imprimir'])->name('facturacion.imprimir');
        Route::get('/facturacion/{factura}/xml',      [FacturaElectronicaController::class, 'xml'])->name('facturacion.xml');
        Route::get('/facturacion/{factura}/cdr',      [FacturaElectronicaController::class, 'cdr'])->name('facturacion.cdr');
    });

    // Proveedores
    Route::resource('proveedores', ProveedorController::class)->except(['show'])->middleware('modulo:proveedores');

    // Reportes
    Route::middleware('modulo:reportes_ventas')->group(function () {
        Route::get('/reportes/ventas',          [ReporteController::class, 'ventas'])->name('reportes.ventas');
        Route::get('/reportes/ventas/csv',      [ReporteController::class, 'ventasCsv'])->name('reportes.ventas.csv');
        Route::get('/reportes/ventas/imprimir', [ReporteController::class, 'ventasImprimir'])->name('reportes.ventas.imprimir');
    });
    Route::middleware('modulo:reportes_inventario')->group(function () {
        Route::get('/reportes/inventario',          [ReporteController::class, 'inventario'])->name('reportes.inventario');
        Route::get('/reportes/inventario/csv',      [ReporteController::class, 'inventarioCsv'])->name('reportes.inventario.csv');
        Route::get('/reportes/inventario/imprimir', [ReporteController::class, 'inventarioImprimir'])->name('reportes.inventario.imprimir');
    });
});

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Configuración de facturación electrónica por tienda (multi-empresa). */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('configuracion_facturacion')) {
            return;
        }

        Schema::create('configuracion_facturacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id')->unique();
            $table->boolean('activo')->default(true);
            // beta_demo | beta | produccion
            $table->string('modo')->default('beta_demo');
            $table->string('ruc')->nullable();
            $table->string('sol_usuario')->nullable();
            $table->text('sol_clave')->nullable();            // cifrada
            $table->string('certificado_path')->nullable();    // ruta relativa en storage/app
            $table->text('certificado_password')->nullable();  // cifrada (si el .pfx la requiere)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_facturacion');
    }
};

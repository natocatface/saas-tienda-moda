<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facturas_electronicas')) {
            return;
        }

        Schema::create('facturas_electronicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id')->nullable()->index();
            $table->unsignedBigInteger('venta_id')->index();
            $table->char('pais', 2)->default('PE');
            $table->string('tipo')->default('BOLETA');       // FACTURA / BOLETA / NOTA_CREDITO
            $table->string('serie', 10);
            $table->unsignedBigInteger('correlativo');
            $table->string('estado')->default('EN_PROCESO'); // ACEPTADO/OBSERVADO/RECHAZADO/ERROR
            $table->string('codigo')->nullable();             // código de respuesta SUNAT
            $table->text('mensaje')->nullable();
            $table->string('id_fiscal')->nullable();          // hash del CDR / CAE
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->timestamps();

            $table->unique(['venta_id']);                     // una venta = un comprobante
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas_electronicas');
    }
};

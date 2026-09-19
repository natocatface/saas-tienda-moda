<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->char('pais', 2);
            $table->string('tipo');
            $table->string('serie', 10);
            $table->unsignedBigInteger('correlativo');
            $table->char('moneda', 3);
            $table->decimal('total', 14, 2)->default(0);
            $table->string('estado')->default('RECIBIDO');
            $table->string('id_fiscal')->nullable();     // CDR/CAE/UUID/CUFE
            $table->string('referencia_externa');          // id de la venta en el ERP
            $table->string('hash')->nullable();
            $table->timestamp('creado_en')->nullable()->useCurrent();
            $table->timestamp('actualizado_en')->nullable();

            // Idempotencia: una venta del ERP = un solo comprobante.
            $table->unique(['empresa_id', 'referencia_externa']);
            // Numeración fiscal única por serie.
            $table->unique(['empresa_id', 'pais', 'serie', 'correlativo']);
            $table->index(['empresa_id', 'estado']);
        });

        Schema::create('comprobante_lineas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('comprobante_id');
            $table->string('descripcion');
            $table->decimal('cantidad', 12, 3);
            $table->decimal('precio_unitario', 14, 2);
            $table->decimal('tasa_impuesto', 5, 4)->default(0);
            $table->string('codigo_producto')->nullable();
            $table->foreign('comprobante_id')->references('id')->on('comprobantes')->cascadeOnDelete();
        });

        Schema::create('facturacion_eventos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('comprobante_id')->index();
            $table->string('tipo_evento');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->json('contexto')->nullable();          // payloads envío/respuesta, actor, ip
            $table->timestamp('creado_en')->useCurrent();
            // Append-only: sin updated_at; nunca se modifica.
        });

        Schema::create('credenciales_pais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('empresa_id');
            $table->char('pais', 2);
            $table->text('certificado_cifrado')->nullable(); // .pfx/.pem cifrado en reposo
            $table->text('secretos_cifrados')->nullable();    // usuario SOL / token PAC / CAF...
            $table->timestamps();
            $table->unique(['empresa_id', 'pais']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credenciales_pais');
        Schema::dropIfExists('facturacion_eventos');
        Schema::dropIfExists('comprobante_lineas');
        Schema::dropIfExists('comprobantes');
    }
};

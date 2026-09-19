<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pagos')) {
            Schema::create('pagos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tienda_id')->nullable()->index();
                $table->foreignId('plan_id')->nullable()->constrained('planes')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->string('numero')->unique();                 // recibo: PAGO-AAAA-NNNNN
                $table->decimal('monto', 10, 2);
                $table->unsignedTinyInteger('periodo_meses')->default(1);
                $table->string('metodo')->default('tarjeta');       // tarjeta, yape, transferencia...
                $table->string('referencia')->nullable();           // id de transacción de la pasarela
                $table->enum('estado', ['pendiente', 'pagado', 'fallido'])->default('pagado');
                $table->date('periodo_inicio')->nullable();
                $table->date('periodo_fin')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

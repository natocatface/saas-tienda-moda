<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Fase 1.2: guarda el string del código QR de SUNAT para la representación impresa. */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facturas_electronicas') && !Schema::hasColumn('facturas_electronicas', 'qr')) {
            Schema::table('facturas_electronicas', function (Blueprint $table) {
                $table->text('qr')->nullable()->after('id_fiscal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('facturas_electronicas', 'qr')) {
            Schema::table('facturas_electronicas', function (Blueprint $table) {
                $table->dropColumn('qr');
            });
        }
    }
};

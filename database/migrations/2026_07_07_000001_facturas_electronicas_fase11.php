<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 1.1: soporta múltiples comprobantes por venta (factura + su nota de
 * crédito) y guarda el motivo, el documento afectado y el ticket de baja.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('facturas_electronicas')) {
            return;
        }

        Schema::table('facturas_electronicas', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas_electronicas', 'motivo')) {
                $table->string('motivo')->nullable()->after('mensaje');
            }
            if (!Schema::hasColumn('facturas_electronicas', 'documento_afectado_id')) {
                $table->unsignedBigInteger('documento_afectado_id')->nullable()->index()->after('venta_id');
            }
            if (!Schema::hasColumn('facturas_electronicas', 'ticket')) {
                $table->string('ticket')->nullable()->after('id_fiscal');
            }
        });

        // Una venta puede tener varios comprobantes (factura + NC): quitar el unique.
        try {
            Schema::table('facturas_electronicas', function (Blueprint $table) {
                $table->dropUnique('facturas_electronicas_venta_id_unique');
            });
        } catch (\Throwable $e) {
            // El índice puede no existir (BD creada ya sin él): ignorar.
        }
    }

    public function down(): void
    {
        Schema::table('facturas_electronicas', function (Blueprint $table) {
            foreach (['motivo', 'documento_afectado_id', 'ticket'] as $col) {
                if (Schema::hasColumn('facturas_electronicas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

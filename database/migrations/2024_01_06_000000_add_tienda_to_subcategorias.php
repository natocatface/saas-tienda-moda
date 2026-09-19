<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subcategorias') && !Schema::hasColumn('subcategorias', 'tienda_id')) {
            Schema::table('subcategorias', function (Blueprint $table) {
                $table->unsignedBigInteger('tienda_id')->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subcategorias') && Schema::hasColumn('subcategorias', 'tienda_id')) {
            Schema::table('subcategorias', function (Blueprint $table) {
                $table->dropColumn('tienda_id');
            });
        }
    }
};

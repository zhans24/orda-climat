<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Если раньше цена хранилась целым числом в тенге — превратим в decimal(12,2)
        // Пример: 12000 -> 12000.00 (без изменения величины)
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        // При откате можно вернуть bigint или как было раньше.
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->default(0)->change();
        });
    }
};

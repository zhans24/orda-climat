<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // связь с продуктом (nullable — для совместимости, и для служебных строк типа "Доставка")
            $table->foreignId('product_id')
                ->nullable()
                ->after('order_id')
                ->constrained('products')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // индексы по частым связям
            $table->index('order_id', 'order_items_order_id_idx');
            $table->index('product_id', 'order_items_product_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex('order_items_order_id_idx');
            $table->dropIndex('order_items_product_id_idx');
            $table->dropColumn('product_id');
        });
    }
};

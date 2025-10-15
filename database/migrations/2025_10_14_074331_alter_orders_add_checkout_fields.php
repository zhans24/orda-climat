<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // «кто» оформляет
            $table->enum('face', ['person', 'company'])
                ->default('person')
                ->after('customer_email');

            // способ доставки + её цена
            $table->enum('delivery_method', ['pickup', 'delivery'])
                ->default('pickup')
                ->after('face');

            $table->decimal('delivery_price', 12, 2)
                ->default(0)
                ->after('delivery_method');

            // суммы
            $table->decimal('subtotal', 12, 2)
                ->default(0)
                ->after('shipping_address');
            // total уже есть — оставляем как есть
        });

        // бэкапов нет: значения по умолчанию покроют старые строки
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['face', 'delivery_method', 'delivery_price', 'subtotal']);
        });
    }
};

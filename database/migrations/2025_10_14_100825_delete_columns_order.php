<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('orders', 'delivery_price')) {
                $table->dropColumn('delivery_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // откат (если вдруг понадобится)
            $table->decimal('delivery_price', 12, 2)->default(0)->after('delivery_method');
            $table->decimal('subtotal', 12, 2)->default(0)->after('shipping_address');
        });
    }
};

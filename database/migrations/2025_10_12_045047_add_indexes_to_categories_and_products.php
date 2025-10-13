<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('categories', function (Blueprint $table) {
            $table->index('parent_id');
            $table->index('is_active');
            $table->index(['is_popular', 'position']);
            $table->index('position');
            $table->index('slug'); // у тебя unique уже есть — индекс не повредит
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            $table->index(['is_featured', 'id']);
            $table->index('slug'); // unique есть — на больших таблицах отдельный индекс полезен
            $table->index('price');
        });
    }

    public function down(): void {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_popular', 'position']);
            $table->dropIndex(['position']);
            $table->dropIndex(['slug']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured', 'id']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['price']);
        });
    }
};

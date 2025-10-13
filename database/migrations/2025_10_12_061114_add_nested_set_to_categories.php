<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Добавляем колонки Nested Set, если их ещё нет
            if (! Schema::hasColumn('categories', '_lft')) {
                $table->unsignedInteger('_lft')->nullable()->index()->after('id');
            }
            if (! Schema::hasColumn('categories', '_rgt')) {
                $table->unsignedInteger('_rgt')->nullable()->index()->after('_lft');
            }

            // НИЧЕГО не делаем с parent_id — индекс уже создан при foreignId()->constrained()
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', '_lft')) {
                $table->dropColumn('_lft');
            }
            if (Schema::hasColumn('categories', '_rgt')) {
                $table->dropColumn('_rgt');
            }
        });
    }
};

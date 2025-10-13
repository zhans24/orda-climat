<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // удаляем лишние, если есть
            if (Schema::hasColumn('leads', 'email'))  $table->dropColumn('email');
            if (Schema::hasColumn('leads', 'source')) $table->dropColumn('source');

            // гарантируем нужные
            if (!Schema::hasColumn('leads', 'name'))    $table->string('name')->nullable();
            if (!Schema::hasColumn('leads', 'phone'))   $table->string('phone');
            if (!Schema::hasColumn('leads', 'message')) $table->text('message')->nullable();

            if (!Schema::hasColumn('leads', 'status')) {
                $table->string('status')->default('new'); // new, in_progress, done, rejected
            }
            $table->index('created_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // вернуть email/source, если откатываем (по желанию)
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            // откат индексов
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });
    }
};

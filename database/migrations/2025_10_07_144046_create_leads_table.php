<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->nullable();   // форма/страница
            $table->string('status')->default('new'); // new, in_progress, done, rejected
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

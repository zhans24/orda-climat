<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();       // артикул/код
            $table->unsignedBigInteger('price')->default(0); // цена в тенге (целое)
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);  // для главной

            $table->text('short')->nullable();
            $table->longText('description')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->json('attributes')->nullable();  // произвольные пары ключ/значение

            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};

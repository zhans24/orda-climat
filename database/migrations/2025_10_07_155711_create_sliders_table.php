<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $t) {
            $t->id();
            $t->string('title')->nullable();
            $t->string('subtitle')->nullable();
            $t->string('link')->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};

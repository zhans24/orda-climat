<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique(); // whatsapp, instagram, tiktok, email, phone, address
            $t->json('value')->nullable(); // {"url": "..."} или {"text": "..."}
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};

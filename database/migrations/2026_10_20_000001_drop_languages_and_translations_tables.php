<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('languages');
    }

    public function down(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->string('name', 64);
            $table->string('native_name', 64);
            $table->string('direction', 3)->default('ltr');
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 12)->index();
            $table->string('group', 64);
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            $table->unique(['locale', 'group', 'key'], 'translations_locale_group_key_unique');
        });
    }
};

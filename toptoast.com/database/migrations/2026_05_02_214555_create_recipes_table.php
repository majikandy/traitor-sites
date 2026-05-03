<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', ['sweet', 'savoury', 'brunch', 'quick']);
            $table->unsignedSmallInteger('time_minutes');
            $table->string('difficulty');
            $table->text('description');
            $table->string('hero_color', 7);
            $table->string('emoji_html');
            $table->json('ingredients_json');
            $table->json('steps_json');
            $table->text('tip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

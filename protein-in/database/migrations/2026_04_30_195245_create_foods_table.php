<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('protein_per_100g', 6, 2);
            $table->decimal('calories_per_100g', 7, 2)->nullable();
            $table->decimal('fat_per_100g', 6, 2)->nullable();
            $table->decimal('carbs_per_100g', 6, 2)->nullable();
            $table->decimal('fibre_per_100g', 6, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('serving_size')->nullable();
            $table->decimal('protein_per_serving', 6, 2)->nullable();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql') {
            Schema::table('foods', function (Blueprint $table) {
                $table->fullText(['name', 'description']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};

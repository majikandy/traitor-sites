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
        Schema::table('foods', function (Blueprint $table) {
            $table->unsignedBigInteger('usda_fdc_id')->nullable()->after('id');
            $table->decimal('cholesterol_mg', 7, 2)->nullable()->after('salt_per_100g');
            $table->decimal('sodium_mg', 7, 2)->nullable()->after('cholesterol_mg');
            $table->decimal('calcium_mg', 7, 2)->nullable()->after('sodium_mg');
            $table->decimal('iron_mg', 7, 3)->nullable()->after('calcium_mg');
            $table->decimal('vitamin_c_mg', 7, 2)->nullable()->after('iron_mg');
            $table->decimal('omega3_g', 6, 3)->nullable()->after('vitamin_c_mg');
            $table->decimal('mono_fat_per_100g', 6, 2)->nullable()->after('omega3_g');
            $table->decimal('poly_fat_per_100g', 6, 2)->nullable()->after('mono_fat_per_100g');
            $table->decimal('water_per_100g', 6, 2)->nullable()->after('poly_fat_per_100g');
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn([
                'usda_fdc_id', 'cholesterol_mg', 'sodium_mg', 'calcium_mg',
                'iron_mg', 'vitamin_c_mg', 'omega3_g', 'mono_fat_per_100g',
                'poly_fat_per_100g', 'water_per_100g',
            ]);
        });
    }
};

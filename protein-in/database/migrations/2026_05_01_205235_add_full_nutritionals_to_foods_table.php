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
        $columns = [
            'usda_fdc_id'      => fn(Blueprint $t) => $t->unsignedBigInteger('usda_fdc_id')->nullable()->after('id'),
            'cholesterol_mg'   => fn(Blueprint $t) => $t->decimal('cholesterol_mg', 7, 2)->nullable()->after('salt_per_100g'),
            'sodium_mg'        => fn(Blueprint $t) => $t->decimal('sodium_mg', 7, 2)->nullable()->after('cholesterol_mg'),
            'calcium_mg'       => fn(Blueprint $t) => $t->decimal('calcium_mg', 7, 2)->nullable()->after('sodium_mg'),
            'iron_mg'          => fn(Blueprint $t) => $t->decimal('iron_mg', 7, 3)->nullable()->after('calcium_mg'),
            'vitamin_c_mg'     => fn(Blueprint $t) => $t->decimal('vitamin_c_mg', 7, 2)->nullable()->after('iron_mg'),
            'omega3_g'         => fn(Blueprint $t) => $t->decimal('omega3_g', 6, 3)->nullable()->after('vitamin_c_mg'),
            'mono_fat_per_100g'=> fn(Blueprint $t) => $t->decimal('mono_fat_per_100g', 6, 2)->nullable()->after('omega3_g'),
            'poly_fat_per_100g'=> fn(Blueprint $t) => $t->decimal('poly_fat_per_100g', 6, 2)->nullable()->after('mono_fat_per_100g'),
            'water_per_100g'   => fn(Blueprint $t) => $t->decimal('water_per_100g', 6, 2)->nullable()->after('poly_fat_per_100g'),
        ];

        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn('foods', $column)) {
                Schema::table('foods', $definition);
            }
        }
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

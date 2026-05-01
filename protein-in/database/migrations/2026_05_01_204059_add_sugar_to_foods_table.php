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
            $table->decimal('sugar_per_100g', 6, 2)->nullable()->after('fibre_per_100g');
            $table->decimal('saturated_fat_per_100g', 6, 2)->nullable()->after('fat_per_100g');
            $table->decimal('salt_per_100g', 6, 2)->nullable()->after('sugar_per_100g');
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn(['sugar_per_100g', 'saturated_fat_per_100g', 'salt_per_100g']);
        });
    }
};

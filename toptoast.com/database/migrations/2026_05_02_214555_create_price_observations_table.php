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
        Schema::create('price_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('retailer_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price_pence')->comment('Always pence, never float');
            $table->date('observed_on');
            $table->enum('source', ['scrape', 'manual', 'csv']);
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->unique(['ingredient_id', 'retailer_id', 'observed_on']);
            $table->index(['ingredient_id', 'retailer_id', 'observed_on']);
            $table->index('observed_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_observations');
    }
};

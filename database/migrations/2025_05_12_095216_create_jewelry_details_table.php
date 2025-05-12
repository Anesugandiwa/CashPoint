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
        Schema::create('jewelry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id');
            $table->enum('material_type', ['Gold', 'Silver', 'Platinum', 'Other']);
            $table->decimal('carat_weight', 8, 2)->nullable();
            $table->string('gemstone_type')->nullable();
            $table->decimal('gemstone_weight', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelry_details');
    }
};

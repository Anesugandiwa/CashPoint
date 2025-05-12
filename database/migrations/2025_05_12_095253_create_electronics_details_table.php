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
        Schema::create('electronics_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id');
            $table->string('brand');
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('specifications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electronics_details');
    }
};

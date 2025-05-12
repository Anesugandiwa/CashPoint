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
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->id();
             $table->foreignId('asset_id');
            $table->string('make');
            $table->string('model');
            $table->string('vehicle_identification_number')->nullable(); 
            $table->integer('mileage')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('color')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_details');
    }
};

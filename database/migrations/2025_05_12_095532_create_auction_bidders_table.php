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
        Schema::create('auction_bidders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id');
            $table->foreignId('user_id');
            $table->decimal('deposit_amount', 15, 2);
            $table->boolean('deposit_paid')->default(false);
            $table->string('payment_reference')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->enum('status', ['Registered', 'Approved', 'Rejected', 'Blacklisted'])->default('Registered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_bidders');
    }
};

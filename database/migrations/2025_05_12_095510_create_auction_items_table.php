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
        Schema::create('auction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id');
            $table->foreignId('asset_id');
            $table->foreignId('loan_id')->nullable();
            $table->decimal('starting_bid', 15, 2);
            $table->decimal('reserve_price', 15, 2)->nullable();
            $table->decimal('current_bid', 15, 2)->nullable();
            $table->foreignId('highest_bidder_id')->nullable()->references('id')->on('users');
            $table->integer('bid_count')->default(0);
            $table->enum('status', ['Listed', 'Active', 'Sold', 'Not Sold', 'Removed'])->default('Listed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_items');
    }
};

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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->string('transaction_reference')->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Pending');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};

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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number')->unique();
            $table->foreignId('user_id');
            $table->foreignId('asset_id');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('loan_period_days');
            $table->decimal('storage_charge_percentage', 5, 2);
            $table->decimal('storage_charge_amount', 15, 2);
            $table->decimal('total_amount_due', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('outstanding_balance', 15, 2);
            $table->date('start_date');
            $table->date('due_date');
            $table->date('grace_period_end_date');
            $table->foreignId('processor_id')->nullable()->references('id')->on('users');
            $table->foreignId('approver_id')->nullable()->references('id')->on('users');
            $table->decimal('credit_score', 5, 2)->nullable(); // FCB credit score
            $table->enum('status', ['Pending', 'Approved', 'Active', 'Paid', 'Defaulted', 'In Auction'])->default('Pending');
            $table->text('notes')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

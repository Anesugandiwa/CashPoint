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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('asset_type_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('user_estimated_value', 15, 2);
            $table->integer('year_of_purchase')->nullable();
            $table->enum('condition', ['New', 'Like New', 'Used', 'Damaged']);
            $table->decimal('market_value', 15, 2)->nullable(); // Estimated by system/evaluator
            $table->decimal('estimated_loan_value', 15, 2)->nullable(); // Calculated based on market value
            $table->decimal('final_evaluation_value', 15, 2)->nullable(); // Final value after physical evaluation
            $table->decimal('final_loan_value', 15, 2)->nullable(); // Final loan amount awarded
            $table->foreignId('evaluator_id')->nullable()->references('id')->on('users');
            $table->foreignId('approver_id')->nullable()->references('id')->on('users');
            $table->text('evaluation_notes')->nullable();
            $table->datetime('evaluation_date')->nullable();
            $table->datetime('approval_date')->nullable();
            $table->enum('status', ['Submitted', 'Market Value Evaluated', 'Final Evaluation Requested', 'Final Evaluation Complete', 'Approved', 'Rejected', 'Active Loan', 'Redeemed', 'In Auction', 'Sold', 'Removed'])->default('Submitted');
            $table->string('storage_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

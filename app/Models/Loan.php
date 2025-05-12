<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'loan_number',
        'user_id',
        'asset_id',
        'principal_amount',
        'interest_rate',
        'loan_period_days',
        'storage_charge_percentage',
        'storage_charge_amount',
        'total_amount_due',
        'amount_paid',
        'outstanding_balance',
        'start_date',
        'due_date',
        'grace_period_end_date',
        'processor_id',
        'approver_id',
        'credit_score',
        'status',
        'notes',
        'last_payment_date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function auctionItem()
    {
        return $this->hasOne(AuctionItem::class);
    }

    public static function generateLoanNumber()
    {
        $prefix = 'LN';
        $timestamp = now()->format('YmdHis');
        $randomDigits = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $randomDigits;
    }

    public function calculateInterest()
    {
        // Calculate interest based on principal, rate, and time
        $principal = $this->principal_amount;
        $interestRate = $this->interest_rate / 100; // Convert percentage to decimal
        $periodInYears = $this->loan_period_days / 365; // Convert days to years
        
        // Simple interest calculation
        $interest = $principal * $interestRate * $periodInYears;
        
        return round($interest, 2);
    }

    public function calculateStorageCharge()
    {
        // Calculate storage charge based on principal and storage charge percentage
        $principal = $this->principal_amount;
        $storageChargeRate = $this->storage_charge_percentage / 100; // Convert percentage to decimal
        
        $storageCharge = $principal * $storageChargeRate;
        
        return round($storageCharge, 2);
    }

    public function calculateTotalAmountDue()
    {
        $interest = $this->calculateInterest();
        $storageCharge = $this->calculateStorageCharge();
        
        $totalAmountDue = $this->principal_amount + $interest + $storageCharge;
        
        return round($totalAmountDue, 2);
    }

    public function updateOutstandingBalance()
    {
        $this->outstanding_balance = $this->total_amount_due - $this->amount_paid;
        $this->save();
        
        return $this->outstanding_balance;
    }

    public function isOverdue()
    {
        return Carbon::now()->greaterThan($this->due_date);
    }

    public function isInGracePeriod()
    {
        $today = Carbon::now();
        return $today->greaterThan($this->due_date) && $today->lessThanOrEqualTo($this->grace_period_end_date);
    }

    public function shouldBeInAuction()
    {
        return Carbon::now()->greaterThan($this->grace_period_end_date) && $this->status !== 'Paid';
    }

    public function processFCBCheck()
    {
        // Implementation for integration with Financial Credit Bureau API
        // This would check the credit score of the customer
        // Return credit score value
    }

}

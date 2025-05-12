<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $fillable = [
        'loan_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'notes',
        'received_by',
    ];
    public function loan(){
        return $this->belongsTo(Loan::class);
    }
    
    public function receiver(){
        return $this->belongsTo(User::class, 'received_by');
    }
    public function updateLoanBalance(){
        if ($this->status === 'Completed') {
            $loan = $this->loan;
            $loan->amount_paid += $this->amount;
            $loan->outstanding_balance = $loan->total_amount_due - $loan->amount_paid;
            $loan->last_payment_date = now();
            
            // Check if loan is fully paid
            if ($loan->outstanding_balance <= 0) {
                $loan->status = 'Paid';
                $loan->outstanding_balance = 0;
                
                // Update asset status
                $asset = $loan->asset;
                $asset->status = 'Redeemed';
                $asset->save();
            }
            
            $loan->save();
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionBidder extends Model
{
     protected $fillable = [
        'auction_id',
        'user_id',
        'deposit_amount',
        'deposit_paid',
        'payment_reference',
        'terms_accepted',
        'status',
    ];



    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'user_id', 'user_id')
            ->whereHas('auctionItem', function ($query) {
                $query->where('auction_id', $this->auction_id);
            });
    }

    public function markDepositPaid($reference)
    {
        $this->deposit_paid = true;
        $this->payment_reference = $reference;
        $this->save();
    }

    public function approveForBidding()
    {
        if ($this->deposit_paid && $this->terms_accepted) {
            $this->status = 'Approved';
            $this->save();
            return true;
        }
        return false;
    }

    public function refundDeposit()
    {
        // Process to refund the deposit if the bidder didn't win any auctions
        // This would integrate with payment processing systems
        
        return true; // Return true if refund successful
    }

    public function hasWonAuctions()
    {
        return $this->bids()
            ->where('is_winning_bid', true)
            ->exists();
    }
}

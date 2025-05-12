<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
     protected $fillable = [
        'auction_item_id',
        'user_id',
        'bid_amount',
        'is_auto_bid',
        'max_auto_bid_amount',
        'is_winning_bid',
    ];



    public function auctionItem()
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processAutoBid($newBidAmount)
    {
        if ($this->is_auto_bid && $this->max_auto_bid_amount > $newBidAmount) {
            // Calculate the minimum increment necessary to outbid
            $increment = 1.00; // This could be based on business rules
            $autoBidAmount = min($newBidAmount + $increment, $this->max_auto_bid_amount);
            
            // Update the auction item with the new auto bid
            $this->auctionItem->updateBid($this->user_id, $autoBidAmount);
            
            return true;
        }
        
        return false;
    }
}

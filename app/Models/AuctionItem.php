<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionItem extends Model
{
     protected $fillable = [
        'auction_id',
        'asset_id',
        'loan_id',
        'starting_bid',
        'reserve_price',
        'current_bid',
        'highest_bidder_id',
        'bid_count',
        'status',
    ];
    public function auction(){
        return $this->belongsTo(Auction::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function highestBidder()
    {
        return $this->belongsTo(User::class, 'highest_bidder_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function updateBid($userId, $bidAmount)
    {
        if ($bidAmount > $this->current_bid) {
            $this->current_bid = $bidAmount;
            $this->highest_bidder_id = $userId;
            $this->bid_count += 1;
            $this->save();
            
            // Create a new bid record
            Bid::create([
                'auction_item_id' => $this->id,
                'user_id' => $userId,
                'bid_amount' => $bidAmount,
                'is_winning_bid' => true,
            ]);
            
            // Update previous winning bid
            Bid::where('auction_item_id', $this->id)
                ->where('is_winning_bid', true)
                ->where('user_id', '!=', $userId)
                ->update(['is_winning_bid' => false]);
                
            return true;
        }
        
        return false;
    }
}

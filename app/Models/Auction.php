<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_datetime',
        'end_datetime',
        'status',
        'created_by'
    ];
     public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(AuctionItem::class);
    }

    public function bidders()
    {
        return $this->hasMany(AuctionBidder::class);
    }

    public function isActive()
    {
        $now = Carbon::now();
        return $this->status === 'Active' && 
               $now->greaterThanOrEqualTo($this->start_datetime) && 
               $now->lessThan($this->end_datetime);
    }

    public function updateStatus()
    {
        $now = Carbon::now();
        
        if ($this->status === 'Scheduled' && $now->greaterThanOrEqualTo($this->start_datetime)) {
            $this->status = 'Active';
            $this->save();
        } elseif ($this->status === 'Active' && $now->greaterThanOrEqualTo($this->end_datetime)) {
            $this->status = 'Completed';
            $this->save();
            
            // Process auction results
            $this->processAuctionResults();
        }
        
        return $this->status;
    }

    public function processAuctionResults()
    {
        foreach ($this->items as $item) {
            if ($item->current_bid && $item->current_bid >= $item->reserve_price) {
                $item->status = 'Sold';
                
                // Update the asset status
                $asset = $item->asset;
                $asset->status = 'Sold';
                $asset->save();
                
                // Update loan status if applicable
                if ($item->loan) {
                    $loan = $item->loan;
                    $loan->status = 'In Auction';
                    $loan->save();
                }
            } else {
                $item->status = 'Not Sold';
            }
            
            $item->save();
        }
    }


}

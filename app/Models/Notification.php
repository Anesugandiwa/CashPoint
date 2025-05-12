<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'related_type',
        'related_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    public static function sendLoanReminder($loan)
    {
        return self::create([
            'user_id' => $loan->user_id,
            'title' => 'Loan Payment Reminder',
            'message' => "Your loan #{$loan->loan_number} is due on {$loan->due_date->format('Y-m-d')}. Please make payment to avoid late fees.",
            'type' => 'loan_due',
            'related_type' => Loan::class,
            'related_id' => $loan->id,
        ]);
    }

    public static function sendAuctionStartNotification($auction, $user)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Auction Starting Soon',
            'message' => "The auction '{$auction->title}' will start on {$auction->start_datetime->format('Y-m-d H:i')}.",
            'type' => 'auction_start',
            'related_type' => Auction::class,
            'related_id' => $auction->id,
        ]);
    }

    public static function sendOutbidNotification($bid, $outbidUser)
    {
        $auctionItem = $bid->auctionItem;
        return self::create([
            'user_id' => $outbidUser->id,
            'title' => 'You Have Been Outbid',
            'message' => "You have been outbid on item '{$auctionItem->asset->title}' in the auction. Current highest bid is now \${$bid->bid_amount}.",
            'type' => 'bid_outbid',
            'related_type' => AuctionItem::class,
            'related_id' => $auctionItem->id,
        ]);
    }
}

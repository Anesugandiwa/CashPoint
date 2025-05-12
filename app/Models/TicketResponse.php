<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{
     protected $fillable = [
        'ticket_id',
        'user_id',
        'response',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFromStaff()
    {
        return $this->user->roles->whereIn('name', ['Super Admin', 'Admin', 'Call Centre Support'])->count() > 0;
    }
}

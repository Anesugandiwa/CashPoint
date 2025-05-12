<?php

namespace App\Models;
use App\Models\User;
use App\Models\TicketResponse;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'assigned_to',
        'resolved_at',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class, 'ticket_id');
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $timestamp = now()->format('YmdHis');
        $randomDigits = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $randomDigits;
    }

    public function assignToStaff($staffId)
    {
        $this->assigned_to = $staffId;
        $this->status = 'In Progress';
        $this->save();
        
        // Create internal note about assignment
        TicketResponse::create([
            'ticket_id' => $this->id,
            'user_id' => $staffId,
            'response' => 'Ticket assigned to staff member.',
            'is_internal' => true,
        ]);
    }

    public function markAsResolved($staffId, $resolutionNote = '')
    {
        $this->status = 'Resolved';
        $this->resolved_at = now();
        $this->save();
        
        // Create resolution note
        if ($resolutionNote) {
            TicketResponse::create([
                'ticket_id' => $this->id,
                'user_id' => $staffId,
                'response' => $resolutionNote,
                'is_internal' => false,
            ]);
        }
    }
}

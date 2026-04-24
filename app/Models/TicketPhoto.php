<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketPhoto extends Model
{
    use HasUuids;

    protected $table = 'ticket_photos';
    protected $guarded = [  
        'id',
    ];

    public function Ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

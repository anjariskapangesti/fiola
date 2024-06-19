<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasUuids;

    protected $table = 'tickets';
    protected $guarded = [  
        'id',
    ];

    public function ticket_photos()
    {
        return $this->hasMany(TicketPhoto::class, 'ticket_id', 'id');
    }

    public function accepted()
    {
        return $this->belongsTo(User::class, 'it_approve_by');
    }
}

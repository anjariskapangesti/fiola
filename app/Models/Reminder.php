<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminders';
    protected $fillable = [
        'user_id',
        'department_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

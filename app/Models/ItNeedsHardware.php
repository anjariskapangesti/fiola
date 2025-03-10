<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItNeedsHardware extends Model
{
    protected $table = 'form_it_need_hardware';
    protected $guarded = ['id'];

    public function ItNeeds()
    {
        return $this->belongsTo(ItNeeds::class);
    }
}

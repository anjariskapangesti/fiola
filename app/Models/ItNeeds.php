<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItNeeds extends Model
{
    protected $table = 'form_it_needs';
    protected $guarded = [
        'id',
    ];

    public function form_it_needs_hardware()
    {
        return $this->hasMany(ItNeedsHardware::class, 'it_needs_id', 'id');
    }


    public function form_it_needs_software()
    {
        return $this->hasMany(ItNeedsSoftware::class, 'it_needs_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

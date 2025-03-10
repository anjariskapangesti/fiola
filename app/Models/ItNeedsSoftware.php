<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItNeedsSoftware extends Model
{
    protected $table = 'form_it_need_software';
    protected $guarded = ['id'];

    public function ItNeeds()
    {
        return $this->belongsTo(ItNeeds::class);
    }
}

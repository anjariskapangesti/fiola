<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Support extends Model
{
    use HasUuids;

    protected $table = 'supports';
    protected $guarded = [  
        'id',
    ];
}

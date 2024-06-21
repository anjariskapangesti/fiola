<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRank extends Model
{
    protected $table = 'public.job_ranks';
    protected $fillable = [
        'code',
        'name'
    ];
}

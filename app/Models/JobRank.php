<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobRank extends Model
{
    protected $table = 'job_ranks';
    protected $fillable = [
        'code',
        'name'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_job_ranks', 'job_rank_id', 'model_id');
    }
}

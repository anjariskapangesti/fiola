<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'code',
        'name'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_departments', 'department_id', 'model_id');
    }
}

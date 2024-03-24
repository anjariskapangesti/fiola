<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasDepartment extends Model
{
    protected $table = 'public.model_has_departments';
    protected $fillable = [
        'model_id',
        'model_type',
        'department_id',
    ];
}

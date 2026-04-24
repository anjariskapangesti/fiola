<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AksesSistem extends Model
{
    use HasFactory;

    use HasUuids;

    protected $table = 'form_akses_sistem';
    protected $guarded = [  
        'id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdDept()
    {
        return $this->belongsTo(Department::class, 'created_dept');
    }
}

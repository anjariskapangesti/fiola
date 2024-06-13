<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Relayout extends Model
{
    use HasUuids;

    protected $table = 'form_relayout';
    protected $fillable = [  
        'uuid',
        'no_reg',
        'budget_type',
        'request_type',
        'project_name',
        'date_finish_plan',
        'location',
        'relayout_type',
        'description',
        'lampiran',

        'purpose',
        'final_status',
        'is_manager_approve',
        'is_it_approve',
        'is_it_mgr_approve',
        'is_on_progress',
        'is_finish',
        'is_confirm',
        'manager_approval_date',
        'it_approval_date',
        'it_mgr_approval_date',
        'on_progress_date',
        'finish_date',
        'manager_note',
        'it_note',
        'it_mgr_note',
        'on_progress_note',
        'finish_note',
        'created_by',
        'created_dept',
        'manager_approve_by',
        'it_approve_by',
        'it_mgr_approve_by',
        'on_progress_by',
        'finish_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

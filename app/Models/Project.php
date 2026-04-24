<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'form_project';

    protected $fillable = [
        'no_reg',
        'npk',
        'fullname',
        'department',
        'phone',
        'nama_project',
        'start_date',
        'end_date',
        'lampiran',
        'kondisi_sebelum',
        'kondisi_target',
        'benefit',
        'alat',
        'cost',
        'final_status',
        'is_manager_approve',
        'is_confirm',
        'manager_approval_date',
        'manager_note',
        'created_by',
        'created_dept',
        'manager_approve_by',
        'is_timeline_active',
        'timeline_order',
        'is_reschedule',
        'reschedule_target_id',
        'is_dir_approve',
        'dir_approve_by',
        'dir_approval_date',
        'dir_note',
        'target_response',
        'target_response_date',
        'target_reschedule_start_date',
        'target_reschedule_end_date',
        'reschedule_reason',
    ];

    protected $casts = [
        'is_timeline_active' => 'boolean',
        'is_reschedule' => 'boolean',
        'is_dir_approve' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'manager_approval_date' => 'datetime',
        'dir_approval_date' => 'datetime',
        'target_response_date' => 'datetime',
        'target_reschedule_start_date' => 'datetime',
        'target_reschedule_end_date' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function directorApproveBy()
    {
        return $this->belongsTo(User::class, 'dir_approve_by');
    }
}
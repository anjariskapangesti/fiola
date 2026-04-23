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
        'is_timeline_active',
        'timeline_order',
        'is_reschedule',
        'reschedule_target_id',
        'is_dir_approve',
        'dir_approve_by',
        'dir_approval_date',
        'dir_note',
    ];

    protected $casts = [
        'is_timeline_active' => 'boolean',
        'is_reschedule' => 'boolean',
        'is_dir_approve' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'manager_approval_date' => 'datetime',
        'it_approval_date' => 'datetime',
        'it_mgr_approval_date' => 'datetime',
        'dir_approval_date' => 'datetime',
        'on_progress_date' => 'datetime',
        'finish_date' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finishBy()
    {
        return $this->belongsTo(User::class, 'finish_by');
    }
}
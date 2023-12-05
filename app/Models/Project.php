<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'form_project';
    protected $fillable = [  
        'no_reg',
        'npk',
        'fullname',
        'department',
        'phone',
        'nama_project',
        'lampiran',
        'kondisi_sebelum',
        'kondisi_target',
        'benefit',
        'alat',
        'final_status',
        'is_manager_approve',
        'is_it_approve',
        'is_it_mgr_approve',
        'is_delay',
        'is_finish',
        'is_confirm',
        'manager_approval_date',
        'it_approval_date',
        'it_mgr_approval_date',
        'delay_date',
        'finish_date',
        'manager_note',
        'it_note',
        'it_mgr_note',
        'delay_note',
        'finish_note',
        'created_by',
        'created_dept',
        'it_approved_by',
        'finish_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

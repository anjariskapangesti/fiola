<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'form_account';
    protected $fillable = [
        'no_reg',
        'budget_type',
        'form_type',
        'account_type',
        'npk',
        'fullname',
        'department',
        'job_rank',
        'phone',
        'company',
        'expired_date',
        'ad_name',
        'ad_name_after',
        'is_email',
        'email_address',

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

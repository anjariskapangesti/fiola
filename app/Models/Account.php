<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'form_account';
    protected $fillable = [
        'budget_type',
        'form_type',
        'npk',
        'fullname',
        'department',
        'phone',
        'company',
        'expired_date',
        'purpose',
        'ad_name',
        'is_email',
        'email_address',
        'final_status',
        'is_manager_approve',
        'is_it_approve',
        'is_it_mgr_approve',
        'is_finish',
        'manager_approval_date',
        'it_approval_date',
        'it_mgr_approval_date',
        'finish_date',
        'manager_note',
        'it_note',
        'it_mgr_note',
        'created_by',
        'created_dept'
    ];
}

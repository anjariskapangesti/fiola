<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vpn extends Model
{
    protected $table = 'form_vpn';
    protected $fillable = [
        'no_reg',
        'npk',
        'fullname',
        'department',
        'phone',
        'email',
        'username',
        'purpose',        
        'final_status',
        'is_manager_approve',
        'is_it_approve',
        'is_it_mgr_approve',
        'is_finish',
        'is_confirm',
        'manager_approval_date',
        'it_approval_date',
        'it_mgr_approval_date',
        'finish_date',
        'manager_note',
        'it_note',
        'it_mgr_note',
        'finish_note',
        'created_by',
        'created_dept'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdDept()
    {
        return $this->belongsTo(Department::class, 'created_dept');
    }
}

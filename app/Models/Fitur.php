<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fitur extends Model
{
    protected $table = 'form_fitur';
    protected $fillable = [  
        'no_reg',
        'username',
        'purpose',
        'file',
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
}

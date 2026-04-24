<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\NewFolderAccess;

class NewFolder extends Model
{
    protected $table = 'form_new_folder';
    protected $fillable = [     
        'no_reg',   
        'form_type',
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

    public function form_new_folder_path()
    {
        return $this->hasMany(NewFolderPath::class, 'new_folder_id', 'id');
    }

    public function form_new_folder_user()
    {
        return $this->hasMany(NewFolderUser::class, 'new_folder_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

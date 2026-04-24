<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FolderAccessPath;

class FolderAccess extends Model
{
    protected $table = 'form_folder_access';
    protected $fillable = [  
        'no_reg',
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
        'created_dept',
        'it_approve_by',
        'finish_by',
    ];

    public function form_folder_access_path()
    {
        return $this->hasMany(FolderAccessPath::class, 'folder_access_id', 'id');
    }

    public function form_folder_access_user()
    {
        return $this->hasMany(FolderAccessUser::class, 'folder_access_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

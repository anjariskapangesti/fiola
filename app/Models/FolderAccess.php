<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FolderAccessPath;
use App\Models\Folder;

class FolderAccess extends Model
{
    protected $table = 'form_folder_access';
    protected $fillable = [        
        'username',
        'purpose',
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

    // public function FolderAccessPath()
    // {
    //     return $this->hasMany(FolderAccessPath::class);
    // }

    public function form_folder_access_path()
    {
        return $this->hasMany(FolderAccessPath::class, 'folder_access_id', 'id');
    }

    public function folder_name()
    {
        return $this->belongsToMany(Folder::class, 'folder', 'id');
    }
}

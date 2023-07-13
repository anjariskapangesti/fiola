<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\NewFolderAccess;

class NewFolder extends Model
{
    protected $table = 'form_new_folder';
    protected $fillable = [        
        'foldername',
        'mainpath',
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

    public function form_new_folder_access()
    {
        return $this->hasMany(NewFolderAccess::class, 'new_folder_id', 'id');
    }
}

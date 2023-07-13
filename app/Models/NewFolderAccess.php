<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\NewFolder;

class NewFolderAccess extends Model
{
    protected $table = 'form_new_folder_access';
    protected $fillable = [        
        'new_folder_id',
        'username',
        'department',
        'permission',     
    ];

    public function NewFolder()
    {
        return $this->belongsTo(NewFolder::class);
    }
}

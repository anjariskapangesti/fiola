<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FolderAccess;

class FolderAccessPath extends Model
{
    protected $table = 'form_folder_access_path';
    protected $fillable = [        
        'folder_access_id',
        'folder',
        'subfolder',
        'permission',        
    ];

    public function FolderAccess()
    {
        return $this->belongsTo(FolderAccess::class);
    }
}

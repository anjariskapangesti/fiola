<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FolderAccess;
use App\Models\Folder;
use App\Models\SubFolder;

class FolderAccessPath extends Model
{
    protected $table = 'form_folder_access_path';
    protected $fillable = [        
        'folder_access_id',
        'folder',
        'subfolder',
        'subsubfolder',
        'permission',     
    ];

    public function FolderAccess()
    {
        return $this->belongsTo(FolderAccess::class);
    }

    public function Folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function Subfolder()
    {
        return $this->belongsTo(SubFolder::class);
    }

}

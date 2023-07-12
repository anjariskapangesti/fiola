<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FolderAccessPath;

class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = [
        'name'
    ];

    public function form_folder_access_path()
    {
        return $this->belongsTo(FolderAccessPath::class);
    }
}

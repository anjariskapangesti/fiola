<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderAccessUser extends Model
{
    protected $table = 'form_folder_access_user';
    protected $fillable = [        
        'folder_access_id',
        'username',
        'department',  
    ];

    public function FolderAccess()
    {
        return $this->belongsTo(FolderAccess::class);
    }
}

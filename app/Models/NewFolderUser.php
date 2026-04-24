<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewFolderUser extends Model
{
    protected $table = 'form_new_folder_user';
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

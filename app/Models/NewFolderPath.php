<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewFolderPath extends Model
{
    protected $table = 'form_new_folder_path';
    protected $fillable = [        
        'new_folder_id',
        'foldername',
        'mainpath',
    ];

    public function NewFolder()
    {
        return $this->belongsTo(NewFolder::class);
    }
}

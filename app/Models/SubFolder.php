<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubFolder extends Model
{
    protected $table = 'subfolders';
    protected $fillable = [
        'folder_id',
        'name'
    ];
}

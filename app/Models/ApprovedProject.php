<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovedProject extends Model
{
    protected $table = 'approved_projects';

    protected $fillable = [
        'project_id',
        'nama',
        'department',
        'hari',
        'bulan',
        'tahun',
        'nama_project',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
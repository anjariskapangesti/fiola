<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Sistem extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected $table = 'form_sistem';
    
    protected $guarded = ['id'];

    public function form_sistem_user()
    {
        return $this->hasMany(SistemUser::class, 'sistem_id', 'id');
    }

    public function form_sistem_app()
    {
        return $this->hasMany(SistemApp::class, 'sistem_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

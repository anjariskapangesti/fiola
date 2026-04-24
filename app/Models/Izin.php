<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Izin extends Model
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

    protected $table = 'form_izin';
    
    protected $guarded = ['id'];

    public function form_izin_user()
    {
        return $this->hasMany(IzinUser::class, 'izin_id', 'id');
    }

    public function form_izin_barang()
    {
        return $this->hasMany(IzinBarang::class, 'izin_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

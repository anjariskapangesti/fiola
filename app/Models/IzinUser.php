<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinUser extends Model
{
    protected $table = 'form_izin_user';
    protected $guarded = ['id'];

    public function Izin()
    {
        return $this->belongsTo(Izin::class);
    }
}

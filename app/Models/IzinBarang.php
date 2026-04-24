<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinBarang extends Model
{
    protected $table = 'form_izin_barang';
    protected $guarded = ['id'];

    public function Izin()
    {
        return $this->belongsTo(Izin::class);
    }
}

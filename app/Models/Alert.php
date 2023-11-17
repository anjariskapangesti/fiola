<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $nohpitmgr = '082260050066';

    public function getNoHpItMgr()
    {
        return $this->nohpitmgr;
    }
}

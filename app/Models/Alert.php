<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $table = 'alerts';
    protected $fillable = [
        'department_name',
        'department_code',
        'name',
        'email',
        'nohp',
    ];

    // protected $nohpitmgr = '081223506433';
    protected $nohpitmgr = ['082125008160', '081223506433'];
    // protected $nohpitmgr = '082260050066';

    public function getNoHpItMgr()
    {
        return $this->nohpitmgr;
    }
}

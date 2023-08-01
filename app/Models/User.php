<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    
    use Notifiable;
    use HasRoles;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'npk', 'name', 'email', 'password', 'nohp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'model_has_departments', 'model_id', 'department_id');
    }

    public function createdDepartments()
    {
        return $this->hasManyThrough(Department::class, ModelHasDepartment::class, 'model_id', 'id', 'id', 'department_id')
            ->where('model_type', User::class);
    }

    public function profileIncomplete()
    {
    // Ganti dengan logika Anda untuk memeriksa kelengkapan profil pengguna.
    // Jika profil belum lengkap, kembalikan true, jika sudah lengkap, kembalikan false.
    return empty($this->name) || empty($this->email) || empty($this->nohp) || empty($this->npk);
    }
}

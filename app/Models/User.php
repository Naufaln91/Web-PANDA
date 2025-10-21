<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method void update(array $attributes = [])
 * @method void fill(array $attributes)
 * @method bool save(array $options = [])
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nomor_hp',
        'username',
        'password',
        'nama_orangtua',
        'nama_anak',
        'kelas_anak',
        'role',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'created_by');
    }

    /**
     * Check if the user is an admin.
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a guru.
     * @return bool
     */
    public function isGuru()
    {
        return $this->role === 'guru';
    }

    /**
     * Check if the user is a wali murid.
     * @return bool
     */
    public function isWaliMurid()
    {
        return $this->role === 'wali_murid';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    protected $fillable = ['nomor_hp', 'role'];

    public static function isWhitelisted($nomorHp)
    {
        return self::where('nomor_hp', $nomorHp)->exists();
    }
}

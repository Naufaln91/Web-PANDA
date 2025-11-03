<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    protected $fillable = ['email', 'role'];

    public static function isWhitelisted($email)
    {
        return self::where('email', $email)->exists();
    }
}

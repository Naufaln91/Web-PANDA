<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Whitelist extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'role'];

    public static function isWhitelisted($email)
    {
        return self::where('email', $email)->exists();
    }
}

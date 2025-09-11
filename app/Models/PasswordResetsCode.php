<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetsCode extends Model
{
    protected $table = 'password_resets_codes';

    protected $fillable = [
        'email',
        'code',
        'expires_at'
    ];
    public $timestamps = true;
}

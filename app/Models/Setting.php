<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'host',
        'from',
        'header',
        'port',
        'encryption',
        'username',
        'password',
    ];
}

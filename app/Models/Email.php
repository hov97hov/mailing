<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'email_id'];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_emails');
    }
}

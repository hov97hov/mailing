<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'archived',
        'message',
        'subject',
        'user_id',
        'contact_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y - H:i',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function file()
    {
       return $this->hasMany(Attachment::class, 'message_id');
    }
}

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
        'from',
        'email',
        'group_id',
        'btn_name',
        'btn_link',
        'mailing_image',
        'image_link',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y - H:i',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function file()
    {
       return $this->hasMany(Attachment::class, 'message_id');
    }
}

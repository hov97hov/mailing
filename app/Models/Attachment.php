<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'message_id',
        'image_name',
        'image_path',
        'image_bottom_name',
        'image_bottom_path',
        'image_link',
    ];
}

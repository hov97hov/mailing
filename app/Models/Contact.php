<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'image',
        'user_id',
    ];

    /**
     * @return HasMany
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function group(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'contact_groups');
    }

    public function message(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}

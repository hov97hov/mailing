<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'sorting',
    ];

    public function contact(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'contact_groups');
    }
}

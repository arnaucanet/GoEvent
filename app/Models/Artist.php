<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_artist');
    }
}

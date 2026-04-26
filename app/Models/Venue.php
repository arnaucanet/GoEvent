<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'city', 'capacity', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

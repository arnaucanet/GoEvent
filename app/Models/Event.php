<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'extra', 'start_date', 'end_date', 'capacity', 'city', 'featured', 'status', 'user_id', 'category_id', 'city', 'venue', 'price'];

    protected $casts = [
        'featured' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

}

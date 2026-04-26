<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'extra', 'start_date', 'end_date',
        'capacity', 'venue_id', 'organizer_id',
        'featured', 'status', 'user_id', 'category_id', 'price',
    ];

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

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function venueRelation()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function types()
    {
        return $this->belongsToMany(EventType::class, 'event_type', 'event_id', 'type_id');
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'event_artist');
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}

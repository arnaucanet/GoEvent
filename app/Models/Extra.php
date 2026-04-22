<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Extra extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

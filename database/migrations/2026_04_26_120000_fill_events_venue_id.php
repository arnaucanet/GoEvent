<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $events = DB::table('events')
            ->whereNull('venue_id')
            ->get(['id', 'city', 'venue', 'capacity']);

        foreach ($events as $event) {
            $venueName = trim((string) ($event->venue ?? ''));
            $cityName = trim((string) ($event->city ?? '')) ?: 'Sin ciudad';
            $name = $venueName !== '' ? $venueName : ($cityName . ' - Recinto');

            $venueId = DB::table('venues')
                ->where('name', $name)
                ->where('city', $cityName)
                ->value('id');

            if (!$venueId) {
                $venueId = DB::table('venues')->insertGetId([
                    'name' => $name,
                    'address' => 'Sin dirección',
                    'city' => $cityName,
                    'capacity' => max((int) ($event->capacity ?? 0), 1),
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('events')->where('id', $event->id)->update(['venue_id' => $venueId]);
        }
    }

    public function down(): void
    {
        // No lo revertimos, los venues creados se quedan.
    }
};

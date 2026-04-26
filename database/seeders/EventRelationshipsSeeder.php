<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class EventRelationshipsSeeder extends Seeder
{
    public function run()
    {
        $typeIds = EventType::pluck('id');
        $artistIds = Artist::pluck('id');
        $venueIds = Venue::pluck('id');
        $userIds = User::pluck('id');

        Event::all()->each(function (Event $event) use ($typeIds, $artistIds, $venueIds, $userIds) {
            // Assign venue + organizer if missing
            if (!$event->venue_id && $venueIds->isNotEmpty()) {
                $event->venue_id = $venueIds->random();
            }
            if (!$event->organizer_id && $userIds->isNotEmpty()) {
                $event->organizer_id = $event->user_id ?? $userIds->random();
            }
            $event->save();

            // N:M types (1-3 per event)
            if ($typeIds->isNotEmpty()) {
                $event->types()->syncWithoutDetaching(
                    $typeIds->random(min(rand(1, 3), $typeIds->count()))->all()
                );
            }

            // N:M artists (0-4 per event)
            if ($artistIds->isNotEmpty()) {
                $count = rand(0, 4);
                if ($count > 0) {
                    $event->artists()->syncWithoutDetaching(
                        $artistIds->random(min($count, $artistIds->count()))->all()
                    );
                }
            }

            // Por defecto los usuarios NO tienen inscripciones ni favoritos.
            // Los pivots de registrations/favorites se crean sólo cuando un usuario
            // los activa desde la interfaz.
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Extra;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtrasTableSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        $defaultExtras = [
            [
                'name' => 'Early Entry (Entrada Anticipada)',
                'description' => 'Acceso antes que el público general',
                'price' => 15.00,
            ],
            [
                'name' => 'Parking Reservado',
                'description' => 'Estacionamiento garantizado cerca del evento',
                'price' => 12.00,
            ],
            [
                'name' => 'Pulsera o Recuerdo del Evento',
                'description' => 'Llévate un recuerdo exclusivo del evento',
                'price' => 6.00,
            ],
        ];

        foreach ($events as $event) {
            foreach ($defaultExtras as $extra) {
                Extra::create([
                    'event_id' => $event->id,
                    'name' => $extra['name'],
                    'description' => $extra['description'],
                    'price' => $extra['price'],
                ]);
            }
        }
    }
}

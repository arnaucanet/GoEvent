<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypesTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Concierto', 'description' => 'Eventos musicales en vivo'],
            ['name' => 'Conferencia', 'description' => 'Charlas y ponencias'],
            ['name' => 'Taller', 'description' => 'Sesiones prácticas y formativas'],
            ['name' => 'Festival', 'description' => 'Eventos multidisciplinares'],
            ['name' => 'Deportivo', 'description' => 'Competiciones y actividades físicas'],
            ['name' => 'Familiar', 'description' => 'Para todos los públicos'],
            ['name' => 'Gastronómico', 'description' => 'Catas, ferias y degustaciones'],
            ['name' => 'Networking', 'description' => 'Encuentros profesionales'],
        ];

        foreach ($types as $t) {
            EventType::firstOrCreate(['name' => $t['name']], $t);
        }
    }
}

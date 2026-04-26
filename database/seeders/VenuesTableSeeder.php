<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenuesTableSeeder extends Seeder
{
    public function run()
    {
        $venues = [
            ['name' => 'Palau Sant Jordi',  'address' => 'Pg. Olímpic, 5-7', 'city' => 'Barcelona', 'capacity' => 17960],
            ['name' => 'WiZink Center',     'address' => 'Av. de Felipe II',  'city' => 'Madrid',    'capacity' => 17453],
            ['name' => 'Bilbao Arena',      'address' => 'Av. Askatasuna 13', 'city' => 'Bilbao',    'capacity' => 10500],
            ['name' => 'Auditorio Rocío Jurado', 'address' => 'Av. Las Razas', 'city' => 'Sevilla',  'capacity' => 8500],
            ['name' => 'Roig Arena',        'address' => 'Av. de les Corts', 'city' => 'Valencia',   'capacity' => 15600],
        ];

        foreach ($venues as $v) {
            Venue::firstOrCreate(['name' => $v['name']], array_merge($v, ['active' => true]));
        }

        Venue::factory()->count(10)->create();
    }
}

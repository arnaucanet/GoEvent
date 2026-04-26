<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
    public function run()
    {
        Artist::factory()->count(20)->create();
    }
}

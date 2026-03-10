<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if there are existing users and categories
        if (\App\Models\User::count() > 0 && \App\Models\Category::count() > 0) {
            
            // Create 10 dummy events
            Event::factory()->count(10)->create();
            
        } else {
            // Log that events could not be seeded because of missing dependencies
            $this->command->info('Events could not be seeded. Ensure Users and Categories exist.');
        }
    }
}

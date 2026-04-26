<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        if (\App\Models\User::count() > 0 && \App\Models\Category::count() > 0) {
            Event::factory()->count(50)->create();

            if ($this->command) {
                $this->command->info('10 events seeded successfully.');
            }
        } else {
            if ($this->command) {
                $this->command->warn('Events could not be seeded. Ensure Users and Categories exist.');
            }
        }
    }
}

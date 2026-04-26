<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica la relación N:M entre Event y User a traves de
     * registrations con campo extra status en el pivote
     */
    public function test_event_registrations_pivot_carries_status(): void
    {
        $event = Event::factory()->create();
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $event->registeredUsers()->attach([
            $alice->id => ['status' => 'inscrito'],
            $bob->id => ['status' => 'asistido'],
        ]);

        $registered = $event->fresh()->registeredUsers->keyBy('id');
        $this->assertEquals('inscrito', $registered[$alice->id]->pivot->status);
        $this->assertEquals('asistido', $registered[$bob->id]->pivot->status);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * si el aforo del evento está lleno,
     * la API rechaza nuevas inscripciones con 422.
     */
    public function test_capacity_full_blocks_new_registration(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $event = Event::factory()->create(['capacity' => 2]);

        // Llenamos el aforo con dos inscripciones existentes
        $event->registeredUsers()->attach(
            User::factory()->count(2)->create()->pluck('id'),
            ['status' => 'inscrito']
        );

        $response = $this->postJson("/api/registrations/{$event->id}");

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Aforo completo');
    }

    /**
     * un usuario autenticado pero sin
     * permiso `event-list` recibe 403 al acceder a la zona admin.
     */
    public function test_user_without_permission_cannot_access_admin_registrations(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/admin/registrations');

        $response->assertStatus(403);
    }
}

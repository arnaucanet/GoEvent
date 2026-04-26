<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Un usuario autenticado puede crear un evento via API.
     * Cubre el flujo con validacion servidor.
     */
    public function test_authenticated_user_can_create_event(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $venue = Venue::factory()->create();
        $category = Category::factory()->create();

        $payload = [
            'title' => 'Nuevo Evento de Prueba',
            'description' => 'Descripción válida',
            'venue_id' => $venue->id,
            'category_id' => $category->id,
            'start_date' => now()->addWeek()->toDateTimeString(),
            'end_date' => now()->addWeek()->addHours(3)->toDateTimeString(),
            'capacity' => 100,
            'status' => 'publicado',
            'featured' => false,
            'price' => 25.00,
        ];

        $response = $this->postJson('/api/events', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('events', [
            'title' => 'Nuevo Evento de Prueba',
            'venue_id' => $venue->id,
        ]);
    }
}

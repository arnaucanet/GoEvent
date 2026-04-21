<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\City;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $events = [
            [
                'title' => 'Concierto de Rock en Vivo',
                'description' => 'Disfruta de las mejores bandas de rock de la ciudad en una noche inolvidable llena de energía y buena música.',
                'image' => 'concierto-rock.jpg'
            ],
            [
                'title' => 'Taller de Fotografía Urbana',
                'description' => 'Aprende las técnicas básicas para capturar la esencia de la ciudad. Trae tu cámara y prepárate para explorar.',
                'image' => 'fotografia-urbana.jpg'
            ],
            [
                'title' => 'Seminario de Marketing Digital',
                'description' => 'Descubre las últimas tendencias en marketing online, redes sociales y estrategias de crecimiento para tu negocio.',
                'image' => 'marketing-digital.jpg'
            ],
            [
                'title' => 'Clase de Yoga al Amanecer',
                'description' => 'Empieza tu día con energía y paz mental. Una sesión de yoga apta para todos los niveles en el parque central.',
                'image' => 'yoga-amanecer.jpg'
            ],
            [
                'title' => 'Exposición de Arte Moderno',
                'description' => 'Visita nuestra galería y sumérgete en el mundo del arte abstracto y contemporáneo de artistas locales.',
                'image' => 'arte-moderno.jpg'
            ],
            [
                'title' => 'Festival Gastronómico',
                'description' => 'Prueba los platos más deliciosos de los mejores chefs de la región. Música, comida y diversión garantizada.',
                'image' => 'festival-gastronomico.jpg'
            ],
            [
                'title' => 'Curso de Programación en Python',
                'description' => 'Introducción intensiva al lenguaje de programación Python. Ideal para principiantes que quieran iniciar en el desarrollo.',
                'image' => 'programacion-python.jpg'
            ],
            [
                'title' => 'Charla sobre Inteligencia Artificial',
                'description' => 'Un panel de expertos discute el futuro de la IA y su impacto en la sociedad y el trabajo.',
                'image' => 'charla-ia.jpg'
            ],
            [
                'title' => 'Torneo de Ajedrez Regional',
                'description' => 'Participa o ven a observar a los mejores estrategas compitiendo por el título regional de ajedrez.',
                'image' => 'torneo-ajedrez.jpg'
            ],
            [
                'title' => 'Feria del Libro Antiguo',
                'description' => 'Encuentra tesoros literarios, primeras ediciones y libros raros en esta feria anual para amantes de la lectura.',
                'image' => 'feria-libreo.jpg'
            ],
        ];

        $event = $this->faker->randomElement($events);
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 48) . ' hours');
        $cityName = City::inRandomOrder()->value('name') ?? $this->faker->city();

        return [
            'title' => $event['title'],
            'description' => $event['description'],
            'city' => $cityName,
            'venue' => $this->faker->company() . ' Arena',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'capacity' => $this->faker->numberBetween(20, 200),
            'price' => $this->faker->randomFloat(2, 0, 120),
            'featured' => $this->faker->boolean(20),
            'status' => 'publicado',
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}

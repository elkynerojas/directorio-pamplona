<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    // Nombres de negocios ficticios típicos de Pamplona
    private static array $businessPrefixes = [
        'Almacén', 'Restaurante', 'Droguería', 'Ferretería', 'Panadería',
        'Cafetería', 'Supermercado', 'Hotel', 'Clínica', 'Sala de Belleza',
        'Taller', 'Distribuidora', 'Tienda', 'Miscelánea', 'Centro',
    ];

    private static array $businessSuffixes = [
        'El Progreso', 'La Esperanza', 'San Agustín', 'La Colonial', 'El Éxito',
        'Los Comuneros', 'La Victoria', 'El Dorado', 'Nueva Era', 'La Pamplonesa',
        'El Central', 'Familiar', 'del Norte', 'La Colina', 'El Parque',
        'Los Andes', 'La Sabana', 'del Oriente', 'La Paz', 'San José',
    ];

    private static array $streets = [
        'Calle 5', 'Calle 6', 'Calle 7', 'Calle 8', 'Calle 9', 'Calle 10',
        'Carrera 5', 'Carrera 6', 'Carrera 7', 'Carrera 8', 'Carrera 9',
        'Diagonal 5', 'Transversal 3', 'Avenida Santander', 'Calle Principal',
    ];

    private static array $neighborhoods = [
        'Centro', 'Barrio Llano Grande', 'La Estrella', 'El Carmen',
        'Villa del Rosario', 'Los Almendros', 'La Esmeralda', 'San Martín',
        'Kennedy', 'El Parque', 'Urbanización El Nogal',
    ];

    private static array $descriptions = [
        'Ofrecemos productos y servicios de la mejor calidad para toda la familia pamplonesa.',
        'Más de 10 años sirviendo a la comunidad de Pamplona con dedicación y compromiso.',
        'Atención personalizada y precios justos para nuestros clientes de siempre.',
        'Los mejores productos del mercado local con garantía de calidad y frescura.',
        'Somos su aliado de confianza en Pamplona, siempre dispuestos a servirle.',
        'Calidad, servicio y buen precio son nuestro compromiso con Pamplona.',
        'Tradición y modernidad al servicio de la comunidad pamplonesa desde hace años.',
        'Contamos con los mejores profesionales para atender todas sus necesidades.',
        'Su satisfacción es nuestra mayor recompensa. Visítenos y compruébelo.',
        'Productos frescos y de primera calidad directo para usted y su familia.',
    ];

    public function definition(): array
    {
        $prefix = $this->faker->randomElement(self::$businessPrefixes);
        $suffix = $this->faker->randomElement(self::$businessSuffixes);
        $name = $prefix . ' ' . $suffix;

        $street = $this->faker->randomElement(self::$streets);
        $number = $this->faker->numberBetween(1, 99);
        $numLocal = $this->faker->numberBetween(1, 20);
        $neighborhood = $this->faker->randomElement(self::$neighborhoods);
        $address = "{$street} #{$number}-{$numLocal}, {$neighborhood}, Pamplona";

        // Coordenadas alrededor del centro de Pamplona
        $lat = $this->faker->latitude(7.35, 7.40);
        $lng = $this->faker->longitude(-72.67, -72.62);

        // Teléfonos colombianos ficticios
        $whatsapp = '573' . $this->faker->numerify('#########');
        $phone = '607' . $this->faker->numerify('#######');

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'short_description' => $this->faker->randomElement(self::$descriptions),
            'long_description' => implode("\n\n", $this->faker->paragraphs(3)),
            'address' => $address,
            'whatsapp' => $whatsapp,
            'phone' => $this->faker->boolean(70) ? $phone : null,
            'email' => $this->faker->boolean(60) ? $this->faker->safeEmail() : null,
            'website' => $this->faker->boolean(30) ? $this->faker->url() : null,
            'instagram' => $this->faker->boolean(50) ? Str::slug($name) : null,
            'facebook' => $this->faker->boolean(40) ? Str::slug($name) : null,
            'tiktok' => null,
            'youtube' => null,
            'schedule' => $this->generateSchedule(),
            'latitude' => round($lat, 8),
            'longitude' => round($lng, 8),
            'main_image' => null,
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(15),
        ];
    }

    private function generateSchedule(): array
    {
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $schedule = [];

        foreach ($days as $day) {
            $isSunday = $day === 'Domingo';
            $isSaturday = $day === 'Sábado';

            $schedule[] = [
                'day' => $day,
                'open' => $isSunday ? null : '08:00',
                'close' => $isSunday ? null : ($isSaturday ? '14:00' : '18:00'),
                'closed' => $isSunday,
            ];
        }

        return $schedule;
    }
}

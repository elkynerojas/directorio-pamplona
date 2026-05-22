<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Restaurantes y Comidas', 'icon' => 'heroicon-o-cake', 'color' => '#EF4444'],
            ['name' => 'Hoteles y Hospedajes', 'icon' => 'heroicon-o-home', 'color' => '#3B82F6'],
            ['name' => 'Farmacias y Droguerías', 'icon' => 'heroicon-o-heart', 'color' => '#10B981'],
            ['name' => 'Tiendas y Ropa', 'icon' => 'heroicon-o-shopping-bag', 'color' => '#8B5CF6'],
            ['name' => 'Ferreterías y Materiales', 'icon' => 'heroicon-o-wrench-screwdriver', 'color' => '#F59E0B'],
            ['name' => 'Supermercados y Tiendas', 'icon' => 'heroicon-o-shopping-cart', 'color' => '#06B6D4'],
            ['name' => 'Peluquerías y Belleza', 'icon' => 'heroicon-o-scissors', 'color' => '#EC4899'],
            ['name' => 'Salud y Clínicas', 'icon' => 'heroicon-o-building-office-2', 'color' => '#14B8A6'],
            ['name' => 'Transporte', 'icon' => 'heroicon-o-truck', 'color' => '#64748B'],
            ['name' => 'Educación', 'icon' => 'heroicon-o-academic-cap', 'color' => '#6366F1'],
            ['name' => 'Entretenimiento', 'icon' => 'heroicon-o-musical-note', 'color' => '#F97316'],
            ['name' => 'Servicios Profesionales', 'icon' => 'heroicon-o-briefcase', 'color' => '#84CC16'],
        ];

        foreach ($categories as $index => $data) {
            Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'icon' => $data['icon'],
                'color' => $data['color'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}

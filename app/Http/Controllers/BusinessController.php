<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Business::query()
            ->with('category')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $businesses = $query
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString()
            ->through(fn ($b) => $this->formatBusiness($b));

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug', 'icon', 'color']);

        return Inertia::render('Index', [
            'businesses' => $businesses,
            'categories' => $categories,
            'filters' => $request->only(['search']),
            'current_category' => null,
        ]);
    }

    public function byCategory(string $slug, Request $request): Response
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Business::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $category->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $businesses = $query
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString()
            ->through(fn ($b) => $this->formatBusiness($b));

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug', 'icon', 'color']);

        return Inertia::render('Index', [
            'businesses' => $businesses,
            'categories' => $categories,
            'filters' => $request->only(['search']),
            'current_category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $business = Business::with(['category', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Business/Show', [
            'business' => [
                'id' => $business->id,
                'name' => $business->name,
                'slug' => $business->slug,
                'short_description' => $business->short_description,
                'long_description' => $business->long_description,
                'history' => $business->history,
                'address' => $business->address,
                'whatsapp' => $business->whatsapp,
                'whatsapp_url' => $business->whatsapp_url,
                'phone' => $business->phone,
                'email' => $business->email,
                'website' => $business->website,
                'instagram' => $business->instagram,
                'facebook' => $business->facebook,
                'tiktok' => $business->tiktok,
                'youtube' => $business->youtube,
                'schedule' => $business->schedule,
                'latitude' => $business->latitude,
                'longitude' => $business->longitude,
                'main_image_url' => $business->main_image_url,
                'is_featured' => $business->is_featured,
                'category' => $business->category ? [
                    'id' => $business->category->id,
                    'name' => $business->category->name,
                    'icon' => $business->category->icon,
                    'color' => $business->category->color,
                ] : null,
                'images' => $business->images->map(fn ($img) => [
                    'id' => $img->id,
                    'url' => $img->url,
                    'alt_text' => $img->alt_text,
                ]),
            ],
        ]);
    }

    private function formatBusiness(Business $b): array
    {
        return [
            'id' => $b->id,
            'name' => $b->name,
            'slug' => $b->slug,
            'short_description' => $b->short_description,
            'address' => $b->address,
            'whatsapp' => $b->whatsapp,
            'whatsapp_url' => $b->whatsapp_url,
            'main_image_url' => $b->main_image_url,
            'is_featured' => $b->is_featured,
            'category' => $b->category ? [
                'id' => $b->category->id,
                'name' => $b->category->name,
                'icon' => $b->category->icon,
                'color' => $b->category->color,
            ] : null,
        ];
    }
}

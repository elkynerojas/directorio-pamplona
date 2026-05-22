<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'long_description',
        'address', 'whatsapp', 'phone', 'email', 'website',
        'instagram', 'facebook', 'tiktok', 'youtube',
        'schedule', 'latitude', 'longitude', 'main_image',
        'is_active', 'is_featured',
    ];

    protected $casts = [
        'schedule' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Business $business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(BusinessImage::class)->orderBy('order');
    }

    public function getMainImageUrlAttribute(): ?string
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }

        return null;
    }

    public function getWhatsappUrlAttribute(): string
    {
        return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->whatsapp);
    }
}

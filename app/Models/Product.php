<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'compare_at_price',
        'category',
        'image_url',
        'gallery',
        'sizes',
        'stock',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'sizes' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function formattedPrice(): string
    {
        return 'PHP '.number_format($this->price);
    }

    public function displayImageUrl(?string $url = null): string
    {
        $imageUrl = $url ?? $this->image_url;

        if (! $imageUrl || ! str_starts_with($imageUrl, '/storage/')) {
            return (string) $imageUrl;
        }

        if (! $this->updated_at) {
            return $imageUrl;
        }

        $separator = str_contains($imageUrl, '?') ? '&' : '?';

        return $imageUrl.$separator.'v='.$this->updated_at->timestamp;
    }
}

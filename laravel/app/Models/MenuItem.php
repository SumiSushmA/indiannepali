<?php

namespace App\Models;

use App\Services\Toast\ToastMenuCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'menu_category_id',
        'slug',
        'name',
        'description',
        'price',
        'is_veg',
        'spice_level',
        'is_popular',
        'image_label',
        'image_path',
        'toast_image_url',
        'toast_pos_id',
        'is_available',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_veg' => 'boolean',
            'spice_level' => 'integer',
            'is_popular' => 'boolean',
            'is_available' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function toastImageUrl(): ?string
    {
        if (filled($this->toast_image_url)) {
            return $this->toast_image_url;
        }

        $toastItem = app(ToastMenuCatalog::class)->findForMenuItem($this);

        if (filled($toastItem['image_url'] ?? null)) {
            return $toastItem['image_url'];
        }

        return null;
    }

    public function customerImagePath(): ?string
    {
        return $this->toastImageUrl() ?? $this->image_path;
    }

    public function toLegacy(): array
    {
        $data = [
            'id' => $this->slug,
            'cat' => $this->category?->slug ?? '',
            'name' => $this->name,
            'price' => (float) $this->price,
            'veg' => $this->is_veg,
            'spice' => $this->spice_level,
            'popular' => $this->is_popular,
            'desc' => $this->description,
            'img' => $this->image_label,
            'image_path' => $this->image_path,
        ];

        if (! $this->is_popular) {
            unset($data['popular']);
        }

        return $data;
    }
}

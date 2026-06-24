<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'gallery_category_id',
        'caption',
        'image_path',
        'grid_span',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'grid_span' => 'integer',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function toLegacy(): array
    {
        return [
            'label' => $this->caption,
            'cat' => $this->category?->name ?? '',
        ];
    }
}

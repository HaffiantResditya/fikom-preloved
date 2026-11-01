<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return \App\Services\CloudinaryService::optimizeUrl($this->image_path, 800) ?? $this->image_path;
        }

        return \Illuminate\Support\Facades\Storage::url($this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return \App\Services\CloudinaryService::optimizeUrl($this->image_path, 480) ?? $this->image_path;
        }

        return $this->url;
    }
}

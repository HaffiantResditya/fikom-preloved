<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;

    protected $fillable = [
        'seller_id',
        'category_id',
        'title',
        'slug',
        'price',
        'condition',
        'description',
        'location',
        'cod_area',
        'status',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Generate WhatsApp link untuk kontak penjual.
     */
    public function getWhatsappLink(): string
    {
        $number = preg_replace('/[^0-9]/', '', $this->seller->whatsapp_number ?? '');
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        $message = urlencode("Halo, saya tertarik dengan barang Anda: *{$this->title}* (Rp " . number_format($this->price, 0, ',', '.') . "). Apakah masih tersedia?");
        return "https://wa.me/{$number}?text={$message}";
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getConditionLabelAttribute(): string
    {
        $cond = strtolower(trim($this->condition ?? ''));
        if (str_contains($cond, 'seperti') || $cond === 'baru') {
            return 'Seperti Baru';
        }
        if (str_contains($cond, 'layak')) {
            return 'Layak Pakai';
        }
        return 'Baik';
    }
}


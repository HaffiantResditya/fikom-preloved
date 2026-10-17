<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public const ORDERED_SLUGS = [
        'perabot-kos',
        'elektronik',
        'buku-modul',
        'alat-praktikum',
        'peralatan-dapur',
        'fashion',
        'atk',
        'olahraga-hobi',
    ];

    public static function getOrderedCategories($withApprovedCount = false)
    {
        $query = self::whereIn('slug', self::ORDERED_SLUGS);

        if ($withApprovedCount) {
            $query->withCount(['products' => function ($q) {
                $q->where('status', 'approved');
            }]);
        }

        return $query->get()->sortBy(function ($cat) {
            return array_search($cat->slug, self::ORDERED_SLUGS);
        })->values();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getDescriptionAttribute(): string
    {
        return match ($this->slug) {
            'perabot-kos'     => 'Kasur, meja belajar, kipas, lemari, dan perlengkapan kamar kos.',
            'elektronik'      => 'Mouse, keyboard, charger, headset, dan gadget pendukung kuliah.',
            'buku-modul'      => 'Buku kuliah, modul, diktat, dan catatan dari kakak tingkat.',
            'alat-praktikum'  => 'Laptop, jas lab, kalkulator, dan alat penunjang praktikum.',
            'peralatan-dapur' => 'Rice cooker, panci, kompor mini, dan alat makan.',
            'fashion'         => 'Jaket almamater, kemeja, sepatu, dan tas kuliah.',
            'atk'             => 'Pulpen, binder, papan ujian, dan perlengkapan tulis lainnya.',
            'olahraga-hobi'   => 'Raket, bola, gitar, dan perlengkapan hobi di waktu luang.',
            default           => 'Temukan barang bekas berkualitas dari sesama mahasiswa FIKOM.',
        };
    }

    public function getIconMetaAttribute(): array
    {
        return match ($this->slug) {
            'perabot-kos' => [
                'bg' => 'background-color: #fee2e2; color: #ef4444;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7v11m0-4h18m0-7v11M3 11h18M7 11V8a1 1 0 011-1h3a1 1 0 011 1v3"/></svg>',
            ],
            'elektronik' => [
                'bg' => 'background-color: #dbeafe; color: #3b82f6;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2zm4 4h2v2h-2V9z"/></svg>',
            ],
            'buku-modul' => [
                'bg' => 'background-color: #fef3c7; color: #f59e0b;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
            ],
            'alat-praktikum' => [
                'bg' => 'background-color: #d1fae5; color: #10b981;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
            ],
            'peralatan-dapur' => [
                'bg' => 'background-color: #ffedd5; color: #f97316;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v3m-4-3v3m8-3v3M4 11h16a1 1 0 011 1 8 8 0 01-8 8 8 8 0 01-8-8 1 1 0 011-1z"/></svg>',
            ],
            'fashion' => [
                'bg' => 'background-color: #fce7f3; color: #ec4899;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7l3.5-3.5a2 2 0 012.828 0L18 7l-2 4-2-1v9H10v-9l-2 1-2-4z"/></svg>',
            ],
            'atk' => [
                'bg' => 'background-color: #f3e8ff; color: #a855f7;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>',
            ],
            'olahraga-hobi' => [
                'bg' => 'background-color: #ccfbf1; color: #14b8a6;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.6 9h16.8M3.6 15h16.8M11.5 3a17 17 0 000 18M12.5 3a17 17 0 010 18"/></svg>',
            ],
            default => [
                'bg' => 'background-color: #f1f5f9; color: #64748b;',
                'svg' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            ],
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Daftar pilihan program studi / jurusan mahasiswa FIKOM.
     */
    public const MAJORS = [
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknik Komputer',
        'Teknik Rekayasa Perangkat Lunak',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp_number',
        'major',
        'npm',
        'faculty',
        'class_year',
        'role',
        'account_status',
        'profile_photo',
        'location',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function isApproved(): bool
    {
        return $this->account_status === 'approved';
    }

    public function isSuspended(): bool
    {
        return in_array($this->account_status, ['suspended', 'rejected']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }

        if (filter_var($this->profile_photo, FILTER_VALIDATE_URL)) {
            return $this->profile_photo;
        }

        if (Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }

        return null;
    }
}

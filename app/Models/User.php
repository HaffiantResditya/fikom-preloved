<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}

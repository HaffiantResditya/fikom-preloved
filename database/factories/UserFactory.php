<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'            => fake()->name(),
            'email'           => fake()->unique()->numerify('mhs###') . '@mhs.udb.ac.id',
            'whatsapp_number' => '0812' . fake()->numerify('########'),
            'password'        => static::$password ??= Hash::make('password'),
            'remember_token'  => Str::random(10),
            'role'            => 'student',
            'account_status'  => 'approved',
        ];
    }

    /**
     * Indicate that the user account status is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'admin',
            'account_status' => 'approved',
        ]);
    }
}

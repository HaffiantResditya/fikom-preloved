<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_students_can_register_with_campus_email(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Mahasiswa FIKOM',
            'email'                 => 'mhs123@mhs.udb.ac.id',
            'whatsapp_number'       => '081234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_registration_fails_with_non_campus_email(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Orang Luar',
            'email'                 => 'orangluar@gmail.com',
            'whatsapp_number'       => '081234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_with_other_university_domains(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Mahasiswa Lain',
            'email'                 => 'mhs@student.fikom.ac.id',
            'whatsapp_number'       => '081234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_new_students_can_register_with_major(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Mahasiswa TI',
            'email'                 => 'mhsti@mhs.udb.ac.id',
            'whatsapp_number'       => '081234567890',
            'major'                 => 'Teknik Informatika',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', [
            'email' => 'mhsti@mhs.udb.ac.id',
            'major' => 'Teknik Informatika',
        ]);
    }
}

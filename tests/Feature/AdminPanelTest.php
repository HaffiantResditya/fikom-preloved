<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_via_admin_login(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@mhs.udb.ac.id',
            'password' => bcrypt('admin12345'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@mhs.udb.ac.id',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_student_cannot_authenticate_via_admin_login(): void
    {
        $student = User::factory()->create([
            'email' => 'student@mhs.udb.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'student@mhs.udb.ac.id',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/user');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_products_management(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/barang');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_sold_products(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/barang-terjual');
        $response->assertStatus(200);
    }

    public function test_student_cannot_access_admin_panel(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student, 'web')->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_approve_product(): void
    {
        $admin = User::factory()->admin()->create();
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $product = Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'title'       => 'Test Barang',
            'price'       => 50000,
            'condition'   => 'Baru',
            'description' => 'Deskripsi test',
            'location'    => 'Kampus',
            'cod_area'    => 'Lobi',
            'status'      => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/barang/{$product->id}/approve");
        $response->assertSessionHas('success');
        $this->assertEquals('approved', $product->fresh()->status);
    }

    public function test_admin_can_reject_product_with_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $product = Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'title'       => 'Test Barang',
            'price'       => 50000,
            'condition'   => 'Baru',
            'description' => 'Deskripsi test',
            'location'    => 'Kampus',
            'cod_area'    => 'Lobi',
            'status'      => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/barang/{$product->id}/reject", [
            'alasan' => 'Foto buram',
            'catatan' => 'Silakan upload ulang dengan foto lebih jelas',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('rejected', $product->fresh()->status);
        $this->assertStringContainsString('Foto buram', $product->fresh()->rejection_reason);
    }

    public function test_admin_can_suspend_user(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create(['account_status' => 'approved']);

        $response = $this->actingAs($admin, 'admin')->post("/admin/user/{$student->id}/suspend", [
            'reason' => 'Melanggar aturan transaksi',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('rejected', $student->fresh()->account_status);
    }
}

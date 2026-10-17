<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_filament_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_products_management(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    public function test_student_cannot_access_filament(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_edit_product_page(): void
    {
        $admin = User::factory()->admin()->create();
        $seller = User::factory()->create();
        $category = \App\Models\Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $product = \App\Models\Product::create([
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

        $response = $this->actingAs($admin)->get("/admin/products/{$product->id}/edit");
        $response->assertStatus(200);
    }
}

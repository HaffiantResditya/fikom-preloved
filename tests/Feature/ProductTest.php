<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_home_and_catalog(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);

        $responseCatalog = $this->get('/catalog');
        $responseCatalog->assertStatus(200);

        $responseCategories = $this->get('/kategori');
        $responseCategories->assertStatus(200);
    }

    public function test_pending_user_is_redirected_to_pending_notice_when_visiting_sell(): void
    {
        $pendingUser = User::factory()->create([
            'email'          => 'mhs.pending@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'pending',
        ]);

        $response = $this->actingAs($pendingUser)->get('/jual');
        $response->assertRedirect(route('pending.notice'));
    }

    public function test_approved_user_can_access_sell_page(): void
    {
        $approvedUser = User::factory()->create([
            'email'          => 'mhs.approved@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $response = $this->actingAs($approvedUser)->get('/jual');
        $response->assertStatus(200);
    }

    public function test_whatsapp_link_is_formatted_correctly(): void
    {
        $seller = User::factory()->create([
            'name'            => 'Penjual FIKOM',
            'email'           => 'penjual@mhs.udb.ac.id',
            'whatsapp_number' => '081234567890',
            'role'            => 'student',
            'account_status'  => 'approved',
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $product = Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'title'       => 'Mouse Logitech',
            'price'       => 100000,
            'condition'   => 'Baru',
            'description' => 'Mouse baru belum dibuka',
            'location'    => 'Kos Palur',
            'cod_area'    => 'Kantin Kampus',
            'status'      => 'approved',
        ]);

        $link = $product->getWhatsappLink();
        $this->assertStringContainsString('https://wa.me/6281234567890', $link);
        $this->assertStringContainsString('Mouse+Logitech', $link);
    }

    public function test_approved_user_can_create_product_with_image_upload(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $seller = User::factory()->create([
            'email'          => 'seller.test@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $category = Category::create([
            'name' => 'Buku',
            'slug' => 'buku',
        ]);

        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('test_product.png', 600, 600);

        $response = $this->actingAs($seller)->post('/jual', [
            'category_id' => $category->id,
            'title'       => 'Buku Pemrograman Web',
            'price'       => 75000,
            'condition'   => 'Bekas Layak Pakai',
            'description' => 'Buku materi semester 3 lengkap.',
            'location'    => 'Kampus UDB Solo',
            'cod_area'    => 'Lobi FIKOM',
            'images'      => [$fakeImage],
        ]);

        $response->assertRedirect(route('products.success'));
        $this->assertDatabaseHas('products', [
            'title'     => 'Buku Pemrograman Web',
            'seller_id' => $seller->id,
            'status'    => 'pending',
        ]);
    }
}


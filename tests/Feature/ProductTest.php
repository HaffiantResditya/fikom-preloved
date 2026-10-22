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

        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->createWithContent('test_product.png', $pngContent);

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

    public function test_seller_can_view_rejected_product_detail(): void
    {
        $seller = User::factory()->create([
            'email'          => 'seller.rejected@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $product = Product::create([
            'seller_id'        => $seller->id,
            'category_id'      => $category->id,
            'title'            => 'Kipas Angin Meja',
            'price'            => 65000,
            'condition'        => 'Layak Pakai',
            'description'      => 'Kipas angin meja masih berfungsi normal.',
            'location'         => 'Kos Palur',
            'cod_area'         => 'Depan Lab Komputer',
            'status'           => 'rejected',
            'rejection_reason' => 'Foto produk tidak lengkap dan kurang jelas.',
        ]);

        // Seller can see detail on products.show
        $response = $this->actingAs($seller)->get(route('products.show', $product->slug));
        $response->assertStatus(200);
        $response->assertSee('Iklan Ditolak oleh Moderator');
        $response->assertSee('Foto produk tidak lengkap dan kurang jelas.');
        $response->assertSee('Perbaiki / Pasang Ulang');
        $response->assertSee('Kembali ke Profile');

        // Profile page has link to view the rejected product
        $profileResponse = $this->actingAs($seller)->get(route('profile.show'));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee(route('products.show', $product->slug));
        $profileResponse->assertSee('RIWAYAT TRANSAKSI');
        $profileResponse->assertSee('Kipas Angin Meja');
        $profileResponse->assertSee('Ditolak');
    }

    public function test_profile_transaction_history_table_displays_all_catalog_items_including_rejected_and_sold(): void
    {
        $seller = User::factory()->create([
            'email'          => 'seller.catalog@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $category = Category::create([
            'name' => 'Serba Ada',
            'slug' => 'serba-ada',
        ]);

        // Create rejected item
        Product::create([
            'seller_id'        => $seller->id,
            'category_id'      => $category->id,
            'title'            => 'Barang Ditolak Test',
            'price'            => 50000,
            'condition'        => 'Layak Pakai',
            'description'      => 'Deskripsi barang ditolak',
            'location'         => 'Kos',
            'cod_area'         => 'Kampus',
            'status'           => 'rejected',
            'rejection_reason' => 'Foto buram',
        ]);

        // Create sold item
        Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'title'       => 'Barang Terjual Test',
            'price'       => 120000,
            'condition'   => 'Seperti Baru',
            'description' => 'Deskripsi barang terjual',
            'location'    => 'Kos',
            'cod_area'    => 'Kampus',
            'status'      => 'sold',
        ]);

        // Create approved/active item
        Product::create([
            'seller_id'   => $seller->id,
            'category_id' => $category->id,
            'title'       => 'Barang Aktif Test',
            'price'       => 85000,
            'condition'   => 'Baik',
            'description' => 'Deskripsi barang aktif',
            'location'    => 'Kos',
            'cod_area'    => 'Kampus',
            'status'      => 'approved',
        ]);

        // Check profile view
        $response = $this->actingAs($seller)->get(route('profile.show'));
        $response->assertStatus(200);
        $response->assertSee('Barang Ditolak Test');
        $response->assertSee('Barang Terjual Test');
        $response->assertSee('Barang Aktif Test');
        $response->assertSee('Selesai');
        $response->assertSee('Aktif');
        $response->assertSee('Ditolak');

        // Check profile edit view
        $editResponse = $this->actingAs($seller)->get(route('profile.edit'));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Barang Ditolak Test');
        $editResponse->assertSee('Barang Terjual Test');
        $editResponse->assertSee('Barang Aktif Test');
    }

    public function test_other_user_cannot_view_rejected_product(): void
    {
        $seller = User::factory()->create([
            'email'          => 'seller.owner@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $otherUser = User::factory()->create([
            'email'          => 'other.buyer@mhs.udb.ac.id',
            'role'           => 'student',
            'account_status' => 'approved',
        ]);

        $category = Category::create([
            'name' => 'Perabot',
            'slug' => 'perabot',
        ]);

        $product = Product::create([
            'seller_id'        => $seller->id,
            'category_id'      => $category->id,
            'title'            => 'Meja Belajar',
            'price'            => 150000,
            'condition'        => 'Baik',
            'description'      => 'Meja kayu kokoh.',
            'location'         => 'Kos Jebres',
            'cod_area'         => 'Kampus',
            'status'           => 'rejected',
            'rejection_reason' => 'Harga tidak wajar.',
        ]);

        $response = $this->actingAs($otherUser)->get(route('products.show', $product->slug));
        $response->assertStatus(404);
    }
}



<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use App\Services\CloudinaryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncImagesToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload existing local product images to Cloudinary and update database with online URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!CloudinaryService::isConfigured()) {
            $this->error('Cloudinary belum dikonfigurasi di file .env!');
            $this->info('Silakan isi CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, dan CLOUDINARY_API_SECRET terlebih dahulu.');
            return 1;
        }

        $images = ProductImage::all();
        $this->info("Memeriksa {$images->count()} gambar di database...");

        $synced = 0;
        $alreadyCloud = 0;
        $failed = 0;

        foreach ($images as $img) {
            if (filter_var($img->image_path, FILTER_VALIDATE_URL)) {
                $alreadyCloud++;
                continue;
            }

            // Path lokal
            $localPath = Storage::disk('public')->path($img->image_path);

            if (!file_exists($localPath)) {
                $this->warn("File lokal tidak ditemukan: {$img->image_path}");
                $failed++;
                continue;
            }

            $this->line("Mengupload: {$img->image_path} ...");
            $cloudUrl = CloudinaryService::upload($localPath, basename($img->image_path));

            if ($cloudUrl) {
                $img->image_path = $cloudUrl;
                $img->save();
                $this->info("✓ Berhasil disinkronkan ke Cloud: {$cloudUrl}");
                $synced++;
            } else {
                $this->error("✗ Gagal upload ke Cloudinary: {$img->image_path}");
                $failed++;
            }
        }

        $this->newLine();
        $this->info("=== RINGKASAN SINKRONISASI ===");
        $this->info("Berhasil diupload ke Cloud: {$synced}");
        $this->info("Sudah online sebelumnya: {$alreadyCloud}");
        if ($failed > 0) {
            $this->warn("Gagal / tidak ditemukan: {$failed}");
        }

        return 0;
    }
}

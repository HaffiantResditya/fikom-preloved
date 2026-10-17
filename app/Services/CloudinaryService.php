<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    public static function isConfigured(): bool
    {
        return !empty(env('CLOUDINARY_CLOUD_NAME')) &&
               !empty(env('CLOUDINARY_API_KEY')) &&
               !empty(env('CLOUDINARY_API_SECRET'));
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param UploadedFile|string $file Path to file, UploadedFile, or raw bytes
     * @param string|null $filename
     * @return string|null The secure HTTPS URL from Cloudinary, or null on failure
     */
    public static function upload($file, ?string $filename = null): ?string
    {
        if (!self::isConfigured()) {
            return null;
        }

        try {
            $cloudName = trim(env('CLOUDINARY_CLOUD_NAME'));
            $apiKey = trim(env('CLOUDINARY_API_KEY'));
            $apiSecret = trim(env('CLOUDINARY_API_SECRET'));

            $timestamp = time();
            $folder = 'fikom_preloved';

            // Parameter signature Cloudinary diurutkan secara alfabetis
            $signatureString = "folder={$folder}&timestamp={$timestamp}" . $apiSecret;
            $signature = sha1($signatureString);

            // Tentukan konten file dan nama file
            if ($file instanceof UploadedFile) {
                $fileContents = file_get_contents($file->getRealPath());
                $name = $filename ?? $file->getClientOriginalName();
            } elseif (is_string($file) && file_exists($file)) {
                $fileContents = file_get_contents($file);
                $name = $filename ?? basename($file);
            } else {
                $fileContents = (string) $file;
                $name = $filename ?? 'product_' . uniqid() . '.jpg';
            }

            $response = Http::timeout(30)
                ->withoutVerifying()
                ->attach('file', $fileContents, $name)
                ->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                    'api_key' => $apiKey,
                    'timestamp' => $timestamp,
                    'folder' => $folder,
                    'signature' => $signature,
                ]);

            if ($response->successful()) {
                $secureUrl = $response->json('secure_url');
                Log::info("Cloudinary upload success: {$secureUrl}");
                return $secureUrl;
            }

            Log::error("Cloudinary upload failed: " . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error("Cloudinary exception: " . $e->getMessage());
            return null;
        }
    }
}

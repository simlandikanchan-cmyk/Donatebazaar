<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class ConvertImagesToWebp extends Command
{
    protected $signature   = 'images:convert-webp';
    protected $description = 'Convert all JPG/PNG images to WebP format';

    public function handle()
    {
        $directories = [
            public_path('images'),
            public_path('storage'),
            storage_path('app/public'),
        ];

        $converted = 0;
        $skipped   = 0;

        foreach ($directories as $dir) {
            if (!File::isDirectory($dir)) continue;

            $files = File::allFiles($dir);

            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());

                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $skipped++;
                    continue;
                }

                $sourcePath = $file->getRealPath();
                $webpPath   = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);

                if (File::exists($webpPath)) {
                    $this->line("⏭ Already exists: " . $file->getFilename());
                    $skipped++;
                    continue;
                }

                try {
                    Image::read($sourcePath)
                         ->toWebp(85)
                         ->save($webpPath);

                    $this->info(" Converted: " . $file->getFilename());
                    $converted++;

                } catch (\Exception $e) {
                    $this->error("❌ Failed: " . $file->getFilename() . " — " . $e->getMessage());
                }
            }
        }

        $this->newLine();
        $this->info("Done! Converted: {$converted} | Skipped: {$skipped}");
    }
}
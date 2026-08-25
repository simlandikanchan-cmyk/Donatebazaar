<?php

namespace App\Services\Campaign;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class CampaignCoverImageService
{
    public function store(UploadedFile $file, string $title): string
    {
        $filename = Str::slug($title).'-'.time().'.webp';
        $savePath = storage_path('app/public/images/'.$filename);

        Image::read($file)
            ->scale(width: 1200)
            ->toWebp(85)
            ->save($savePath);

        return 'images/'.$filename;
    }
}
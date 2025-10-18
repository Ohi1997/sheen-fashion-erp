<?php

namespace App\Actions;

use App\Models\Banner;
use Illuminate\Http\UploadedFile;

class UploadBannerAction
{
    public function execute(Banner $banner, UploadedFile $image): void
    {
        $banner->clearMediaCollection('images');
        $banner->addMedia($image)->toMediaCollection('images');
    }
}

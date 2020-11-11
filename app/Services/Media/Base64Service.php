<?php

namespace App\Services\Media;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Base64Service implements ImageService
{
    /**
     * @inheritDoc
     */
    public function save(string $path, string $image): string
    {
        $image = base64_decode(str_replace(' ', '+', str_replace('data:image/png;base64,', '', $image)));
        Storage::disk('public')->put(
            $fullPath = $path . '/' . Str::random(40) . '.jpg',
            $image,
            'public'
        );
        return $fullPath;
    }
}

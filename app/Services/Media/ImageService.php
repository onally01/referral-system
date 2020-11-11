<?php

namespace App\Services\Media;

interface ImageService
{
    /**
     * Save the Image.
     *
     * @param string $path
     * @param string $image
     *
     * @return string
     */
    public function save(string $path, string $image): string;
}

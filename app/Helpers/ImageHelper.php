<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Build a full URL for a user image.
     */
    public static function user(?string $image): string
    {
        if (!empty($image) && filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        $imageName = $image ?: 'default.png';
        $imageName = ltrim(str_replace(['public/uploads/users/', 'uploads/users/'], '', $imageName), '/');

        return url('public/uploads/users/' . $imageName);
    }
}

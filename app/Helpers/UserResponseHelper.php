<?php

namespace App\Helpers;

use App\Models\User;

class UserResponseHelper
{
    /**
     * Prepare a user payload for API responses with a normalized image path.
     */
    public static function format(User $user): array
    {
        $data = $user->toArray();
        $data['image'] = ImageHelper::user($user->image);

        return $data;
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        Auth::logout();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your password has been updated successfully. Now you can login to access the dashboard.'
            );
    }
}

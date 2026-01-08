<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class DeleteAccountController extends Controller
{
    //Function for delete account
   public function destroy(Request $request)
{
    $user = auth()->user();

    if ($request->user_id != $user->id) {
        abort(403);
    }

    auth()->logout();
    $user->delete();

    return redirect()->route('login')->with(
        'account_deleted',
        'Your account has been permanently deleted. We’re sorry to see you go.'
    );
}



}

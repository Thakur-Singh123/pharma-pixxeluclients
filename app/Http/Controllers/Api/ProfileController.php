<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserResponseHelper;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends BaseController
{
    /**
     * Update the authenticated user's account information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }

        $requestedUserId = (int) $request->input('id', $user->id);
        //echo $requestedUserId; exit;

        if ($requestedUserId !== $user->id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not allowed to update this account.',
                'data' => null,
            ], 403);
        }

        $imageName = $user->image ?? 'default.png';

        if ($request->hasFile('image')) {
            $this->deleteExistingImage($user->image);

            $file = $request->file('image');
            $imageName = $this->storeImage($file);
        }

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'image' => $imageName,
        ]);

        $user->refresh();

        $success['status'] = 200;
        $success['message'] = 'Account updated successfully.';
        $success['data'] = UserResponseHelper::format($user);

        return response()->json($success, 200);
    }

    /**
     * Delete an existing image if present and not the default placeholder.
     */
    protected function deleteExistingImage(?string $image): void
    {
        if (!$image || $image === 'default.png' || filter_var($image, FILTER_VALIDATE_URL)) {
            return;
        }

        $imagePath = public_path('uploads/users/' . ltrim($image, '/'));

        if (file_exists($imagePath) && is_file($imagePath)) {
            @unlink($imagePath);
        }
    }

    /**
     * Store the uploaded image and return the stored file name.
     */
    protected function storeImage($file): string
    {
        $destination = public_path('uploads/users');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($originalName ?: 'avatar') . '-' . time() . '.' . $extension;

        $file->move($destination, $filename);

        return $filename;
    }
}

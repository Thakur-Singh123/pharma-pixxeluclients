<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MobileNotificationController extends Controller
{
     // 🔥 1) Get All Notifications (web + mobile same format)
   public function list()
{
    $user = Auth::user();

    // Fetch all notifications
    $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

    // ❗ If user has no notifications
    if ($notifications->count() === 0) {
        return response()->json([
            "status" => 200,
            "message" => "No notifications found.",
            "data" => []
        ]);
    }

    // Format notifications
    $data = $notifications->map(function ($n) {
        return [
            "id"        => $n->id,
            "title"     => $n->data['title'] ?? "",
            "message"   => $n->data['message'] ?? "",
            "type"      => $n->data['type'] ?? "",
            "icon"      => $n->data['icon'] ?? "fas fa-bell",
            "url"       => $n->data['url'] ?? "",
            "is_read"   => $n->read_at ? true : false,
            "created_at"=> $n->created_at->format("Y-m-d H:i:s"),
            "time_ago"  => $n->created_at->diffForHumans(),
        ];
    });

    return response()->json([
        "status" => 200,
        "message" => "Notifications fetched successfully",
        "data" => $data
    ]);
}



  public function read($id)
{
    $notification = Auth::user()->notifications()->where('id', $id)->first();

    if (!$notification) {
        return response()->json([
            "status" => 404,
            "message" => "Notification not found."
        ], 404);
    }

    // Already read check
    if ($notification->read_at !== null) {
        return response()->json([
            "status" => 200,
            "message" => "Notification already marked as read."
        ]);
    }

    // Mark as read now
    $notification->markAsRead();

    return response()->json([
        "status" => 200,
        "message" => "Notification marked as read."
    ]);
}

public function readAll()
{
    $user = Auth::user();

    // Check if there are any unread notifications
    if ($user->unreadNotifications->count() == 0) {
        return response()->json([
            "status" => 200,
            "message" => "No unread notifications found."
        ]);
    }

    // Mark all unread as read
    $user->unreadNotifications->markAsRead();

    return response()->json([
        "status" => 200,
        "message" => "All notifications marked as read."
    ]);
}

}

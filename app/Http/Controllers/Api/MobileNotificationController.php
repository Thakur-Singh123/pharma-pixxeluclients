<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MobileNotificationController extends Controller
{
     
    //Function for all notification list
    public function list() {
        //Geu auth login
        $user = Auth::user();
        //Get notifications
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
        //Check if auth exist or not
        if ($notifications->count() === 0) {
            return response()->json([
                "status" => 200,
                "message" => "No notifications found.",
                "data" => []
            ]);
        }
        //Notifications
        $data = $notifications->map(function ($n) {
            return [
                "id" => $n->id,
                "title" => $n->data['title'] ?? "",
                "message" => $n->data['message'] ?? "",
                "type" => $n->data['type'] ?? "",
                "icon" => $n->data['icon'] ?? "fas fa-bell",
                "url" => $n->data['url'] ?? "",
                "is_read" => $n->read_at ? true : false,
                "created_at" => $n->created_at->format("Y-m-d H:i:s"),
                "time_ago" => $n->created_at->diffForHumans(),
            ];
        });
        //Response
        return response()->json([
            "status" => 200,
            "message" => "Notifications fetched successfully",
            "data" => $data
        ]);
    }

    //Function for read notification
    public function read($id) {
        //Get notification
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        //Check if notification found or not
        if (!$notification) {
            return response()->json([
                "status" => 404,
                "message" => "Notification not found."
            ], 404);
        }
        //Check if notification already read or not
        if ($notification->read_at !== null) {
            return response()->json([
                "status" => 200,
                "message" => "Notification already marked as read."
            ]);
        }
        //Mark notification
        $notification->markAsRead();
        //Response
        return response()->json([
            "status" => 200,
            "message" => "Notification marked as read."
        ]);
    }
    
    //Function for read all notification
    public function readAll() {
        //Get auth detail
        $user = Auth::user();
        //Check if notification exists or not
        if ($user->unreadNotifications->count() == 0) {
            return response()->json([
                "status" => 200,
                "message" => "No unread notifications found."
            ]);
        }
        //Mark all unread as read
        $user->unreadNotifications->markAsRead();
        //Response
        return response()->json([
            "status" => 200,
            "message" => "All notifications marked as read."
        ]);
    }
}

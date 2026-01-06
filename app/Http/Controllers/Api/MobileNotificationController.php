<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Models\DeviceToken;

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
                // "type" => $n->data['type'] ?? "",
                "icon" => $n->data['icon'] ?? "fas fa-bell",
                // "url" => $n->data['url'] ?? "",
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


    //test notification
    public function test(Request $request, FirebaseService $firebase)
    {
        //ECHO "test";EXIT;
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'nullable|string',
            'body'    => 'nullable|string',
            'data'    => 'nullable|array',
        ]);

        //  DB se user ke saare device tokens nikalo
        $tokens = DeviceToken::where('user_id', $request->user_id)
                    ->pluck('token');
         
        if ($tokens->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No device tokens found for this user'
            ], 404);
        }

        $responses = [];

        // Har device token par notification bhejo
        foreach ($tokens as $token) {
            $responses[] = $firebase->sendNotification(
                $token,
                $request->title ?? 'Test Notification',
                $request->body  ?? 'Laravel Firebase notification working 🚀',
                $request->data  ?? []
            );
        }

        return response()->json([
            'success' => true,
            'sent_to_tokens' => $tokens->count(),
            'firebase_response' => $responses
        ]);
    }

}

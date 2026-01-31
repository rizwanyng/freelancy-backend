<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Models\User;

class AdminNotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->user_id) {
            AdminNotification::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'body' => $request->body,
            ]);
        } else {
            // Send to all users
            $users = User::all();
            foreach ($users as $user) {
                AdminNotification::create([
                    'user_id' => $user->id,
                    'title' => $request->title,
                    'body' => $request->body,
                ]);
            }
        }

        return response()->json(['message' => 'Notification queued successfully']);
    }

    public function getUnread(Request $request)
    {
        $notifications = AdminNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = AdminNotification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }
        
        return response()->json(['success' => true]);
    }
}

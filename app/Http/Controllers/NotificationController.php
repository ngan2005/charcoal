<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect based on notification data if link exists
            if (isset($notification->data['link']) && $notification->data['link'] !== '#') {
                return redirect($notification->data['link']);
            }
        }
        
        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}

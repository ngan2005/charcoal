<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Get chat history for current user or session.
     */
    public function getMessages(Request $request)
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $messages = DB::table('support_messages')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('UserID', $userId);
                } else {
                    $query->where('SessionID', $sessionId);
                }
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Store a new support message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = DB::table('support_messages')->insert([
            'UserID' => Auth::id(),
            'SessionID' => session()->getId(),
            'Message' => $request->message,
            'IsFromAdmin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Show staff support page.
     */
    public function index()
    {
        return view('staff.support.index');
    }

    // ========== ADMIN/STAFF FUNCTIONS ==========

    /**
     * Get all conversations (for admin/staff).
     */
    public function getAllConversations()
    {
        $conversations = DB::table('support_messages as sm')
            ->select(
                'sm.UserID',
                'u.FullName',
                'u.Email',
                DB::raw('MAX(sm.created_at) as last_message_time'),
                DB::raw('(SELECT COUNT(*) FROM support_messages sm2 WHERE sm2.UserID = sm.UserID AND (sm2.ReadAt IS NULL OR sm2.IsFromAdmin = 0)) as unread_count')
            )
            ->leftJoin('users as u', 'sm.UserID', '=', 'u.UserID')
            ->groupBy('sm.UserID', 'u.FullName', 'u.Email')
            ->orderByDesc('last_message_time')
            ->get();

        return response()->json($conversations);
    }

    /**
     * Get messages for a specific user (for admin/staff).
     */
    public function getUserMessages($userId)
    {
        $messages = DB::table('support_messages')
            ->where('UserID', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        DB::table('support_messages')
            ->where('UserID', $userId)
            ->where('IsFromAdmin', false)
            ->whereNull('ReadAt')
            ->update(['ReadAt' => now()]);

        return response()->json($messages);
    }

    /**
     * Send reply from admin/staff.
     */
    public function reply(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,UserID',
            'message' => 'required|string|max:1000',
        ]);

        DB::table('support_messages')->insert([
            'UserID' => $request->user_id,
            'SessionID' => null,
            'Message' => $request->message,
            'IsFromAdmin' => true,
            'ReadAt' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

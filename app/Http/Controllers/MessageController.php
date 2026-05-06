<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', auth()->id())
            ->with('sender')
            ->latest()
            ->get()
            ->values();

        return view('account.messages', compact('messages'));
    }

    public function markAsRead(Message $message)
    {
        if ($message->user_id === auth()->id()) {
            $message->update(['is_read' => true]);
        }
        return response()->json(['success' => true]);
    }
}

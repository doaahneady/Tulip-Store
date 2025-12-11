<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Get users with admin, IT, or IT super privileges
        $specialUsers = User::where(function($query) {
                $query->where('is_admin', true)
                      ->orWhere('is_it', true)
                      ->orWhere('is_it_super', true);
            })
            ->with('role')
            ->get();

        // Get recent conversations
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function($message) {
                return $message->sender_id == Auth::id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first();
            });

        return view('chat.index', compact('specialUsers', 'conversations'));
    }

    public function show(User $user)
    {
        // Get messages between current user and selected user
        $messages = Message::where(function($query) use ($user) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        // Get users with admin, IT, or IT super privileges
        $specialUsers = User::where(function($query) {
                $query->where('is_admin', true)
                      ->orWhere('is_it', true)
                      ->orWhere('is_it_super', true);
            })
            ->with('role')
            ->get();

        return view('chat.show', compact('user', 'messages', 'specialUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getMessages($userId)
    {
        // Get messages between current user and selected user
        $messages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
    
    /**
     * Broadcast message to users by role
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'role_id' => 'nullable|exists:roles,id',
            'send_to_all' => 'nullable|boolean'
        ]);
        
        // Get target users
        if ($request->send_to_all) {
            // Send to all users with roles (workers)
            $users = User::whereNotNull('role_id')->get();
        } elseif ($request->role_id) {
            // Send to specific role
            $users = User::where('role_id', $request->role_id)->get();
        } else {
            return response()->json(['error' => 'يجب تحديد الدور أو إرسال للجميع'], 400);
        }
        
        $sentCount = 0;
        foreach ($users as $user) {
            if ($user->id !== Auth::id()) {
                Message::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $user->id,
                    'message' => $request->message,
                    'is_broadcast' => true
                ]);
                $sentCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'sent_count' => $sentCount,
            'message' => "تم إرسال الرسالة إلى {$sentCount} مستخدم"
        ]);
    }
    
    /**
     * Get users by role for broadcast
     */
    public function getUsersByRole(Request $request)
    {
        $roleId = $request->get('role_id');
        
        if ($roleId) {
            $users = User::where('role_id', $roleId)->with('role')->get();
        } else {
            $users = User::whereNotNull('role_id')->with('role')->get();
        }
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
}

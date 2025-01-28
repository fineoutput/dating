<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnverifyUser;
use App\Models\UserOtp;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class ChatController extends Controller
{

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);
    
        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'status' => 'sent',
        ]);
    
        $chatArray = [
            'id' => $chat->id,
            'sender_id' => $chat->sender_id,
            'receiver_id' => $chat->receiver_id,
            'message' => $chat->message,
            'status' => $chat->status,
        ];
    
        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $chatArray,
        ], 201);
    }

    public function getMessages(Request $request)
    {

        $receiverId = $request->header('receiver_id');

        if (!$receiverId) {
            return response()->json([
                'message' => 'Receiver ID is required.',
            ], 400);
        }
    
        $receiverExists = User::find($receiverId);
        if (!$receiverExists) {
            return response()->json([
                'message' => 'Receiver not found.',
            ], 404);
        }
    
        $messages = Chat::where(function ($query) use ($receiverId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($receiverId) {
                $query->where('sender_id', $receiverId)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $messagesArray = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'status' => $message->status,
            ];
        });
    
        return response()->json([
            'message' => 'Messages fetched successfully.',
            'data' => $messagesArray,
        ]);
    }

    public function updateMessageStatus(Request $request)
    {
        // Validate the input
        $request->validate([
            'message_id' => 'required',
            'status' => 'required|in:sent,delivered,read',
        ]);

        // Update message status
        $message = Chat::find($request->message_id);
        $message->status = $request->status;
        $message->save();

        return response()->json([
            'message' => 'Message status updated successfully.',
            'data' => $message
        ]);
    }

}

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
    
        // Create the chat record
        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'status' => 'sent',
        ]);
    
        // Format the created_at time into a human-readable format
        $timeAgo = Carbon::parse($chat->created_at)->diffForHumans();
    
        // Prepare the response data
        $chatArray = [
            'id' => $chat->id,
            'sender_id' => $chat->sender_id,
            'receiver_id' => $chat->receiver_id,
            'message' => $chat->message,
            'status' => $chat->status,
            'sent_time' => $timeAgo,  // Add the formatted time here
        ];
    
        // Return the response with the chat details and sent time
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

        // Fetch messages between the authenticated user and the receiver
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

        // Format the messages and add the "time ago" feature
        $messagesArray = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'status' => $message->status,
                'sent_time' => Carbon::parse($message->created_at)->diffForHumans(), // Add "time ago" feature
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
    
        $message = Chat::find($request->message_id);

        if (!$message) {
            return response()->json([
                'message' => 'Message not found.',
            ], 404);
        }
    
        $message->status = $request->status;
        $message->save();
    
        $messageData = [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'message' => $message->message,
            'status' => $message->status,
        ];
    
        return response()->json([
            'message' => 'Message status updated successfully.',
            'data' => $messageData,
        ]);
    }
}

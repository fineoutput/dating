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
            'receiver_rendom' => 'required',
            'message' => 'required|string|max:255',
        ]);
    
        
        $receiver_rendom = User::where('rendom',$request->receiver_rendom)->first();

        $generatedCodes = [];

        function generateUniqueCode(&$generatedCodes) {
            $randomCode = rand(100000, 999999);

            while (in_array($randomCode, $generatedCodes)) {
                $randomCode = rand(100000, 999999);
            }

            $generatedCodes[] = $randomCode;
        
            return $randomCode;
        }

        $code = generateUniqueCode($generatedCodes);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_rendom->id,
            'message' => $request->message,
            'status' => 'sent',
            'rendom' => $code,
        ]);
    
        // Format the created_at time into a human-readable format
        $timeAgo = Carbon::parse($chat->created_at)->diffForHumans();
        $rendom_1 = User::where('id',$chat->sender_id)->first();
        $rendom_2 = User::where('id',$chat->receiver_id)->first();
        // Prepare the response data
        $chatArray = [
            // 'id' => $chat->id,
            'sender_rendom' => $rendom_1->rendom,
            'receiver_rendom' => $rendom_2->rendom,
            'message' => $chat->message,
            'rendom' => $chat->rendom,
            'status' => $chat->status,
            'sent_time' => $timeAgo,  
        ];
    
        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $chatArray,
            'status' => 200,
        ]);
    }


   public function getMessages(Request $request)
    {
        $receiverId = $request->header('receiver_rendom');

        if (!$receiverId) {
            return response()->json([
                'message' => 'Receiver ID is required.',
            ]);
        }

        $receiverExists = User::where('rendom',$receiverId)->first();

        $receve_id = $receiverExists->id;

        if (!$receiverExists) {
            return response()->json([
                'message' => 'Receiver not found.',
            ]);
        }

        // Fetch messages between the authenticated user and the receiver
        $messages = Chat::where(function ($query) use ($receve_id) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $receve_id);
            })
            ->orWhere(function ($query) use ($receve_id) {
                $query->where('sender_id', $receve_id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

            
        $messagesArray = $messages->map(function ($message) {
            $rendom_1 = User::where('id',$message->sender_id)->first();
            $rendom_2 = User::where('id',$message->receiver_id)->first();
            return [
                'rendom' => $message->rendom,
                'sender_rendom' => $rendom_1->rendom,
                'receiver_rendom' => $rendom_2->rendom,
                'message' => $message->message,
                'status' => $message->status,
                'sent_time' => Carbon::parse($message->created_at)->diffForHumans(), // Add "time ago" feature
            ];
        });

        return response()->json([
            'message' => 'Messages fetched successfully.',
            'data' => $messagesArray,
            'status' => 200,
        ]);
    }


    public function updateMessageStatus(Request $request)
    {
        $request->validate([
            'rendom' => 'required',
            'status' => 'required|in:sent,delivered,read',
        ]);

        $message = Chat::where('rendom',$request->rendom)->first();

        if (!$message) {
            return response()->json([
                'message' => 'Message not found.',
                'data' => [],
                'status' => 200,
            ]);
        }

        $message->status = $request->status;
        $message->save();

        $rendom_1 = User::where('id',$message->sender_id)->first();
        $rendom_2 = User::where('id',$message->receiver_id)->first();

        $messageData = [
            'rendom' => $message->rendom,
            'sender_rendom' => $rendom_1->rendom,
            'receiver_rendom' => $rendom_2->rendom,
            'message' => $message->message,
            'status' => $message->status,
            'sent_time' => Carbon::parse($message->created_at)->diffForHumans(), 
        ];

        return response()->json([
            'message' => 'Message status updated successfully.',
            'data' => $messageData,
            'status' => 200,
        ]);
    }

}

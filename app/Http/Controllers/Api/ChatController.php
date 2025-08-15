<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnverifyUser;
use App\Models\UserOtp;
use App\Models\Chat;
use App\Models\ActivitySubscription;
use App\Models\DatingSubscription;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Mail\OtpMail;
use App\Models\Activity;
use App\Models\CoinCategory;
use App\Models\Cupid;
use App\Models\OtherInterest;
use App\Models\SlideLike;
use App\Models\UserSubscription;
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
        'type' => 'required',
        'chat_type' => 'required',
        'activity_id' => 'required',
    ]);

    $sender = Auth::user();
    $now = Carbon::now('Asia/Kolkata');

    $receiver = User::where('rendom', $request->receiver_rendom)->first();
    if (!$receiver) {
        return response()->json([
            'message' => 'User Not Found',
            'data' => [],
            'status' => 200,
        ]);
    }

    $activeSubscription = UserSubscription::where('user_id', $sender->id)
        ->where('type', 'Activitys')
        ->where('is_active', 1)
        ->where('activated_at', '<=', $now)
        ->where('expires_at', '>=', $now)
        ->first();

    $allowedCount = 0;
    if ($activeSubscription) {
        $coinCategory = CoinCategory::find($activeSubscription->plan_id);
        if ($coinCategory) {
            $allowedCount = $coinCategory->interest_messages_coin; 
        } else {
            $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
            $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
        }
    } else {
        $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
        $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
    }

    $startDate = Carbon::parse($sender->created_at)->startOfDay();
    $nowStartOfDay = $now->copy()->startOfDay();

    $currentIntervalStart = $startDate;

    while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
        $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();

        $count = Chat::where('sender_id', $sender->id)
            ->where('chat_type', 'activity')
            ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
            ->count();

        if ($count >= $allowedCount) {
            $messageText = $activeSubscription
                ? 'You have used all your message coins for this month. Please purchase or renew your plan.'
                : 'Please subscribe to send more messages.';

            return response()->json([
                'message' => $messageText,
                'data' => [
                    'interval_start' => $currentIntervalStart->toDateString(),
                    'interval_end' => $currentIntervalEnd->toDateString(),
                    'messages_sent' => $count,
                    'allowed_messages' => $allowedCount,
                ],
                'status' => 203,
            ]);
        }

        $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
    }

    if ($receiver->id == $sender->id) {
        return response()->json([
            'message' => 'You cannot send message to yourself.',
            'data' => [],
            'status' => 400,
        ]);
    }

    $code = rand(100000, 999999);

    while (Chat::where('rendom', $code)->exists()) {
        $code = rand(100000, 999999);
    }

        $mainActivity = Activity::where('rendom', $request->activity_id)->first();

    $chat = Chat::create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'message' => $request->message,
        'status' => 'sent',
        'rendom' => $code,
        'chat_type' => $request->chat_type,
        'activity_id' => $mainActivity->id,
    ]);

    // Format sent time
    $timeAgo = Carbon::parse($chat->created_at)->diffForHumans();

    return response()->json([
        'message' => 'Message sent successfully.',
        'data' => [
            'sender_rendom' => $sender->rendom,
            'receiver_rendom' => $receiver->rendom,
            'message' => $chat->message,
            'rendom' => $chat->rendom,
            'chat_type' => $chat->chat_type,
            'status' => $chat->status,
            'sent_time' => $timeAgo,
        ],
        'status' => 200,
    ]);
}


//     public function sendMessage(Request $request)
// {
//     $request->validate([
//         'receiver_rendom' => 'required',
//         'message' => 'required|string|max:255',
//         'type' => 'required',
//         'chat_type' => 'required',
//     ]);

//     // Get the current user
//     $sender = Auth::user();

//     $receiver_rendom = User::where('rendom', $request->receiver_rendom)->first();
//     if (!$receiver_rendom) {
//         return response()->json([
//             'message' => 'User Not Found',
//             'data' => [],
//             'status' => 200,
//         ], 200);
//     }

//     $message_count = ActivitySubscription::orderBy('id','DESC')->first(); 
//     $dating_message_count = DatingSubscription::orderBy('id','DESC')->first(); 

//     $message_count_1 = (int) $message_count->message_count ?? ''; 
//     $message_count_2 = (int) $dating_message_count->swipe_message ?? ''; 



//     $type = $request->input('type');

//     if($type == 0){
//     if ($sender->subscription == 0) {

//         $messagesSentThisWeek = Chat::where('sender_id', $sender->id)
//             ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
//             ->count();


//         if ($messagesSentThisWeek >= $message_count_1) {
//             return response()->json([
//                 'message' => 'Subscribe to send more messages.',
//                 'data' => [],
//                 'status' => 200,
//             ], 200);
//         }
//     }
// }
// else{
//     if ($sender->subscription == 0) {

//         $messagesSentThisWeek = Chat::where('sender_id', $sender->id)
//             ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
//             ->count();


//         if ($messagesSentThisWeek >= $message_count_2) {
//             return response()->json([
//                 'message' => 'Subscribe to send more messages for dating.',
//                 'data' => [],
//                 'status' => 200,
//             ], 200);
//         }
//     }
// }


//     $generatedCodes = [];
//     function generateUniqueCode(&$generatedCodes) {
//         $randomCode = rand(100000, 999999);

//         while (in_array($randomCode, $generatedCodes)) {
//             $randomCode = rand(100000, 999999);
//         }

//         $generatedCodes[] = $randomCode;
//         return $randomCode;
//     }

//     // Generate a unique code for the message
//     $code = generateUniqueCode($generatedCodes);

//     // Create the chat record
//     $chat = Chat::create([
//         'sender_id' => $sender->id,
//         'receiver_id' => $receiver_rendom->id,
//         'message' => $request->message,
//         'status' => 'sent',
//         'rendom' => $code,
//         'chat_type' => $request->chat_type,
//     ]);

//     // Format the created_at time into a human-readable format
//     $timeAgo = Carbon::parse($chat->created_at)->diffForHumans();
//     $rendom_1 = User::where('id', $chat->sender_id)->first();
//     $rendom_2 = User::where('id', $chat->receiver_id)->first();

//     // Prepare the response data
//     $chatArray = [
//         'sender_rendom' => $rendom_1->rendom,
//         'receiver_rendom' => $rendom_2->rendom,
//         'message' => $chat->message,
//         'rendom' => $chat->rendom,
//         'chat_type' => $chat->chat_type,
//         'status' => $chat->status,
//         'sent_time' => $timeAgo,
//     ];

//     return response()->json([
//         'message' => 'Message sent successfully.',
//         'data' => $chatArray,
//         'status' => 200,
//     ]);
// }


public function getMessages(Request $request)
{
    $authUser = Auth::user();
    $receiverRendom = $request->input('receiver_rendom');

    $receiver = User::where('rendom', $receiverRendom)->first();

    if (!$receiver) {
        return response()->json([
            'message' => 'Data Not Found',
            'data' => [],
            'status' => 200,
        ]);
    }

    $receiverId = $receiver->id;

    // ✅ Decode only once
    $decodedImages = json_decode($receiver->profile_image, true);
    $profileImageUrl = null;

    if (is_array($decodedImages) && count($decodedImages) > 0) {
        $firstImage = reset($decodedImages);
        $profileImageUrl = url('') . '/uploads/app/profile_images/' . ltrim($firstImage, '/');
    }

    // ✅ Fetch messages between sender & receiver
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

    $messagesArray = $messages->map(function ($message) use ($profileImageUrl) {
        $sender = User::find($message->sender_id);
        $receiver = User::find($message->receiver_id);

        return [
            'rendom' => $message->rendom,
            'chat_type' => $message->chat_type,
            'sender_rendom' => $sender->rendom ?? null,
            'receiver_rendom' => $receiver->rendom ?? null,
            'profile_image' => $profileImageUrl, // ✅ Same for all
            'message' => $message->message,
            'status' => $message->status,
            'sent_time' => Carbon::parse($message->created_at)->diffForHumans(),
        ];
    });


     $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // 🔹 Get all activities by this user where status = 2
    $matchingActivities = Activity::where('user_id', $user->id)
                                  ->where('status', 2)
                                  ->get();

    $activityIds = $matchingActivities->pluck('id');

    // $Activitiesren = Activity::where('rendom', $request->rendom)->first();

    $interestRelations = OtherInterest::where('user_id', $user->id)->orderBy('id','DESC')
                                      ->get();

    $oppositeUserIds = $interestRelations->map(function ($relation) use ($user) {
        return $relation->user_id == $user->id ? $relation->user_id_1 : $relation->user_id;
    })->unique()->values();

    $userDetailsFromInterest2 = User::where('rendom',$receiverRendom)->whereIn('id', $oppositeUserIds)->get()->map(function ($userItem) use ($interestRelations, $user) {
    // Find the matching interest relation for this user
    $matchingRelation = $interestRelations->first(function ($relation) use ($userItem, $user) {
        return ($relation->user_id == $user->id && $relation->user_id_1 == $userItem->id) ||
               ($relation->user_id_1 == $user->id && $relation->user_id == $userItem->id);
    });

        $userItem->interest_activity_id = $matchingRelation->activity_id ?? null;
        $userItem->confirm = $matchingRelation->confirm ?? null;

        return $userItem;
    });

    // 🔹 Get matched users from SlideLike table
    $likeUser = SlideLike::where('matched_user', $user->id);
    $likeUserDetails = $likeUser->pluck('matching_user');
    $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

    // 🔹 Map interest users
    $userList = $userDetailsFromInterest2->map(function ($userItem) use ($user) {
        $imagePath = null;
        if ($userItem->profile_image) {
            $images = json_decode($userItem->profile_image, true); 
            if (is_array($images) && count($images)) {
                $imagePath = reset($images);
            }
        }

        $chat = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $userItem->id)
                    ->orderBy('id', 'DESC')
                    ->first();

            $activityname = Activity::where('id',$userItem->interest_activity_id)->first();
        return [
            'id' => $userItem->id,
            'user_rendom' => $userItem->rendom,
            'name' => $userItem->name,
            'activity_id' => $userItem->interest_activity_id,
            'title' => $activityname->title,
            'status' => $userItem->confirm,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
        ];
    });

    $likeUserList = $likeUserDetails2->map(function ($userItem) use ($user) {
        $imagePath = null;
        if ($userItem->profile_image) {
            $images = json_decode($userItem->profile_image, true); 
            if (is_array($images) && count($images)) {
                $imagePath = reset($images);
            }
        }

        $chat = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $userItem->id)
                    ->orderBy('id', 'DESC')
                    ->first();

        return [
            'id' => $userItem->id,
            'user_rendom' => $userItem->rendom,
            'name' => $userItem->name,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
        ];
    });

    // 🔹 Get Cupid matches
    $CupidMatches = Cupid::where('user_id_1', $user->id)
                         ->orWhere('user_id_2', $user->id)
                         ->get()
                         ->unique();

    $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
        $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
        $matchedUser = User::find($matchedUserId);

        if (!$matchedUser) return null;

        $images = json_decode($matchedUser->profile_image, true);
        $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;

        $chat = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $matchedUser->id)
                    ->orderBy('id', 'DESC')
                    ->first();

        return [
            'id' => $matchedUser->id,
            'user_rendom' => $matchedUser->rendom,
            'name' => $matchedUser->name,
            'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
            'form' => 'match',
            'last_message' => $chat->message ?? null,
        ];
    })->filter(); // remove nulls

    // 🔹 Combine and remove duplicates, prioritize 'match'
    $matchUsers = collect($userList)
                    ->merge($likeUserList)
                    ->merge($matchedUsers)
                    ->sortByDesc(function ($user) {
                        return $user['form'] === 'match' ? 2 : 1;
                    })
                    ->unique('id')
                    ->values();


    return response()->json([
        'message' => 'Messages fetched successfully.',
        'data' => $messagesArray,
        'pactup' => $userList,
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

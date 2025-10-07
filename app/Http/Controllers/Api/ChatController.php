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


//  public function sendMessage(Request $request)
// {
//     $request->validate([
//         'receiver_rendom' => 'required',
//         'message' => 'required',
//         'type' => 'required',
//         'chat_type' => 'required',
//         'activity_id' => 'nullable',
//     ]);

//     $sender = Auth::user();
//     $now = Carbon::now('Asia/Kolkata');

//     $receiver = User::where('rendom', $request->receiver_rendom)->first();
//     if (!$receiver) {
//         return response()->json([
//             'message' => 'User Not Found',
//             'data' => [],
//             'status' => 200,
//         ]);
//     }

//     $activeSubscription = UserSubscription::where('user_id', $sender->id)
//         ->where('type', 'Activitys')
//         ->where('is_active', 1)
//         ->where('activated_at', '<=', $now)
//         ->where('expires_at', '>=', $now)
//         ->first();

//     $allowedCount = 0;
//     if ($activeSubscription) {
//         $coinCategory = CoinCategory::find($activeSubscription->plan_id);
//         if ($coinCategory) {
//             $allowedCount = $coinCategory->interest_messages_coin; 
//         } else {
//             $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
//             $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
//         }
//     } else {
//         $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
//         $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
//     }

//     $startDate = Carbon::parse($sender->created_at)->startOfDay();
//     $nowStartOfDay = $now->copy()->startOfDay();

//     $currentIntervalStart = $startDate;

//     while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
//         $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();

//         $count = Chat::where('sender_id', $sender->id)
//             ->where('chat_type', 'activity')
//             ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
//             ->count();

//         if ($count >= $allowedCount) {
//             $messageText = $activeSubscription
//                 ? 'You have used all your message coins for this month. Please purchase or renew your plan.'
//                 : 'Please subscribe to send more messages.';

//             return response()->json([
//                 'message' => $messageText,
//                 'data' => [
//                     'interval_start' => $currentIntervalStart->toDateString(),
//                     'interval_end' => $currentIntervalEnd->toDateString(),
//                     'messages_sent' => $count,
//                     'allowed_messages' => $allowedCount,
//                 ],
//                 'status' => 203,
//             ]);
//         }

//         $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
//     }

//     if ($receiver->id == $sender->id) {
//         return response()->json([
//             'message' => 'You cannot send message to yourself.',
//             'data' => [],
//             'status' => 400,
//         ]);
//     }

//     $code = rand(100000, 999999);

//     while (Chat::where('rendom', $code)->exists()) {
//         $code = rand(100000, 999999);
//     }

//       $mainActivity = '';

//     if($request->activity_id){
//         $mainActivity = Activity::where('rendom', $request->activity_id)->first();
//     }
    
//     $chat = Chat::create([
//         'sender_id' => $sender->id,
//         'receiver_id' => $receiver->id,
//         'message' => $request->message,
//         'status' => 'sent',
//         'rendom' => $code,
//         'chat_type' => $request->chat_type,
//         'activity_id' => $mainActivity->id ?? '',
//     ]);

//     // Format sent time
//     $timeAgo = Carbon::parse($chat->created_at)->diffForHumans();

//     return response()->json([
//         'message' => 'Message sent successfully.',
//         'data' => [
//             'sender_rendom' => $sender->rendom,
//             'receiver_rendom' => $receiver->rendom,
//             'message' => $chat->message,
//             'rendom' => $chat->rendom,
//             'chat_type' => $chat->chat_type,
//             'status' => $chat->status,
//             'sent_time' => $timeAgo,
//         ],
//         'status' => 200,
//     ]);
// }


public function sendMessage(Request $request)
{
    $request->validate([
        'receiver_rendom' => 'required',
        'message' => 'required',
        'type' => 'required',
        'chat_type' => 'required',
        'send_type' => 'required|in:single,group',
        'activity_id' => 'nullable',
    ]);

    $sender = Auth::user();
    $now = Carbon::now('Asia/Kolkata');

   
        // return $receiverRendoms;

    if ($request->send_type === 'group') {
         $receiverRendomsRaw = $request->receiver_rendom;

$receiverRendoms = is_array($receiverRendomsRaw)
    ? $receiverRendomsRaw
    : json_decode($receiverRendomsRaw, true);

        $receivers = User::whereIn('rendom', $receiverRendoms)->get()->keyBy('rendom');
        // return $receivers;
        $responses = [];

        // Collect all valid receiver IDs (excluding self)
        $validReceiverIds = [];

        foreach ($receiverRendoms as $receiverRendom) {
            $receiver = $receivers->get($receiverRendom);

            if (!$receiver) {
                $responses[] = [
                    'receiver_rendom' => $receiverRendom,
                    'message' => 'Receiver not found.',
                    'status' => 404,
                ];
                continue;
            }

            if ($receiver->id == $sender->id) {
                $responses[] = [
                    'receiver_rendom' => $receiverRendom,
                    'message' => 'You cannot send message to yourself.',
                    'status' => 400,
                ];
                continue;
            }

            // Add to valid receiver IDs array
            $validReceiverIds[] = $receiver->id;
        }

        // Implode IDs into a comma-separated string
        $implodedReceiverIds = implode(',', $validReceiverIds);

        // Now send message individually to valid users
        foreach ($validReceiverIds as $receiverId) {
            $receiver = User::find($receiverId);

            // Subscription & message limit check
            $activeSubscription = UserSubscription::where('user_id', $sender->id)
                ->where('type', 'Activitys')
                ->where('is_active', 1)
                ->where('activated_at', '<=', $now)
                ->where('expires_at', '>=', $now)
                ->first();

            $allowedCount = 0;
            if ($activeSubscription) {
                $coinCategory = CoinCategory::find($activeSubscription->plan_id);
                $allowedCount = $coinCategory ? $coinCategory->interest_messages_coin : 0;
            } else {
                $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
                $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
            }

            $startDate = Carbon::parse($sender->created_at)->startOfDay();
            $nowStartOfDay = $now->copy()->startOfDay();
            $currentIntervalStart = $startDate;

            $limitExceeded = false;
            while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
                $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();
                $count = Chat::where('sender_id', $sender->id)
                    ->where('chat_type', 'activity')
                    ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
                    ->count();

                if ($count >= $allowedCount) {
                    $limitExceeded = true;
                    break;
                }

                $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
            }

            if ($limitExceeded) {
                $responses[] = [
                    'receiver_id' => $receiverId,
                    'message' => 'Message limit reached.',
                    'status' => 203,
                ];
                continue;
            }

            $code = rand(100000, 999999);
            while (Chat::where('rendom', $code)->exists()) {
                $code = rand(100000, 999999);
            }

            $mainActivity = null;
            if ($request->activity_id) {
                $mainActivity = Activity::where('rendom', $request->activity_id)->first();
            }

        }
        
            $chat = Chat::create([
                'sender_id' => $sender->id,
                'receiver_id' => $implodedReceiverIds,
                'message' => $request->message,
                'status' => 'sent',
                'rendom' => $code,
                'send_type' => $request->send_type,
                'chat_type' => $request->chat_type,
                'activity_id' => $mainActivity->id ?? null,
            ]);

            $responses[] = [
                'receiver_rendom' => $receiver->rendom,
                'message' => $chat->message,
                'status' => 200,
                'rendom' => $chat->rendom,
                'sent_time' => Carbon::parse($chat->created_at)->diffForHumans(),
            ];

        return response()->json([
            'message' => 'Group messages processed.',
            'data' => $responses,
            'receiver_ids' => $implodedReceiverIds, // returning here
            'status' => 200,
        ]);
    }else{
        $receiverRendoms = $request->receiver_rendom;
        $receiver = User::where('rendom', $receiverRendoms)->first();

        if (!$receiver) {
            return response()->json([
                'message' => 'User Not Found',
                'data' => [],
                'status' => 200,
            ]);
        }

    if ($receiver->id == $sender->id) {
        return response()->json([
            'message' => 'You cannot send message to yourself.',
            'data' => [],
            'status' => 400,
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
        $allowedCount = $coinCategory ? $coinCategory->interest_messages_coin : 0;
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

    $code = rand(100000, 999999);
    while (Chat::where('rendom', $code)->exists()) {
        $code = rand(100000, 999999);
    }

    $mainActivity = null;
    if ($request->activity_id) {
        $mainActivity = Activity::where('rendom', $request->activity_id)->first();
    }

    $chat = Chat::create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'message' => $request->message,
        'status' => 'sent',
        'rendom' => $code,
         'send_type' => $request->send_type,
        'chat_type' => $request->chat_type,
        'activity_id' => $mainActivity->id ?? null,
    ]);

    return response()->json([
        'message' => 'Message sent successfully.',
        'data' => [
            'sender_rendom' => $sender->rendom,
            'receiver_rendom' => $receiver->rendom,
            'message' => $chat->message,
            'rendom' => $chat->rendom,
            'chat_type' => $chat->chat_type,
            'status' => $chat->status,
            'sent_time' => Carbon::parse($chat->created_at)->diffForHumans(),
        ],
        'status' => 200,
    ]);
    }
}




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

    $decodedImages = json_decode($receiver->profile_image, true);
    $profileImageUrl = null;

    if (is_array($decodedImages) && count($decodedImages) > 0) {
        $firstImage = reset($decodedImages);
        $profileImageUrl = url('') . '/uploads/app/profile_images/' . ltrim($firstImage, '/');
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

    $messagesArray = $messages->map(function ($message) use ($profileImageUrl) {
        $sender = User::find($message->sender_id);
        $receiver = User::find($message->receiver_id);

        return [
            'rendom' => $message->rendom,
            'chat_type' => $message->chat_type,
            'sender_rendom' => $sender->rendom ?? null,
            'receiver_rendom' => $receiver->rendom ?? null,
            'profile_image' => $profileImageUrl,
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

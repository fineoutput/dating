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
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

use App\Notifications\ChatMessageNotification;



class ChatController extends Controller
{

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }



// public function sendMessage(Request $request)
// {
//     $request->validate([
//         'receiver_rendom' => 'required',
//         'message' => 'required',
//         'type' => 'required',
//         'chat_type' => 'required',
//         'send_type' => 'required|in:single,group',
//         'activity_id' => 'nullable',
//     ]);

//     $sender = Auth::user();
//     $now = Carbon::now('Asia/Kolkata');

   
//         // return $receiverRendoms;

//     if ($request->send_type === 'group') {
//          $receiverRendomsRaw = $request->receiver_rendom;

//  $receiverRendoms = is_array($receiverRendomsRaw)
//     ? $receiverRendomsRaw
//     : json_decode($receiverRendomsRaw, true);

//         $receivers = User::whereIn('rendom', $receiverRendoms)->get()->keyBy('rendom');
//         // return $receivers;
//         $responses = [];

//         // Collect all valid receiver IDs (excluding self)
//         $validReceiverIds = [];

//         foreach ($receiverRendoms as $receiverRendom) {
//             $receiver = $receivers->get($receiverRendom);

//             if (!$receiver) {
//                 $responses[] = [
//                     'receiver_rendom' => $receiverRendom,
//                     'message' => 'Receiver not found.',
//                     'status' => 404,
//                 ];
//                 continue;
//             }

//             if ($receiver->id == $sender->id) {
//                 $responses[] = [
//                     'receiver_rendom' => $receiverRendom,
//                     'message' => 'You cannot send message to yourself.',
//                     'status' => 400,
//                 ];
//                 continue;
//             }

//             // Add to valid receiver IDs array
//             $validReceiverIds[] = $receiver->id;
//         }

//         $implodedReceiverIds = implode(',', $validReceiverIds);

//         foreach ($validReceiverIds as $receiverId) {
//             $receiver = User::find($receiverId);

//             $title = $request->title ?? 'New Group Message';

//                 $firebaseService = new FirebaseService();

//                         $sent = $firebaseService->sendNotification(
//                         $receiver->fcm_token,
//                         $title,
//                         $request->message,
//                          $data = [
//                             'sender_rendom' => $sender->rendom,
//                             'receiver_rendom' => $receiver->rendom,
//                             'message' => $request->message,
//                             'status' => 'sent',
//                             'sent_time' => $now->diffForHumans(),
//                             'screen' => 'Chat',
//                         ]
//                     );

//                     if ($sent) {
//                             Log::info("✅ Notification sent to user ID {$receiver->id}");
//                         } else {
//                             Log::warning("No FCM token for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
//                             $responses[] = [
//                                 'receiver_rendom' => $receiver->rendom,
//                                 'message' => 'No FCM token available.',
//                                 'status' => 400,
//                             ];
//                         }

//             // Subscription & message limit check
//             $activeSubscription = UserSubscription::where('user_id', $sender->id)
//                 ->where('type', 'Activitys')
//                 ->where('is_active', 1)
//                 ->where('activated_at', '<=', $now)
//                 ->where('expires_at', '>=', $now)
//                 ->first();

//             $allowedCount = 0;
//             if ($activeSubscription) {
//                 $coinCategory = CoinCategory::find($activeSubscription->plan_id);
//                 $allowedCount = $coinCategory ? $coinCategory->interest_messages_coin : 0;
//             } else {
//                 $activitySub = ActivitySubscription::orderBy('id', 'desc')->first();
//                 $allowedCount = $activitySub ? (int)$activitySub->message_count : 0;
//             }

//             $startDate = Carbon::parse($sender->created_at)->startOfDay();
//             $nowStartOfDay = $now->copy()->startOfDay();
//             $currentIntervalStart = $startDate;

//             $limitExceeded = false;
//             while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
//                 $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();
//                 $count = Chat::where('sender_id', $sender->id)
//                     ->where('chat_type', 'activity')
//                     ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
//                     ->count();

//                 if ($count >= $allowedCount) {
//                     $limitExceeded = true;
//                     break;
//                 }

//                 $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
//             }

//             if ($limitExceeded) {
//                $messageText = $activeSubscription
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
//             }

//              $code = rand(100000, 999999);
//             while (Chat::where('rendom', $code)->exists()) {
//                 $code = rand(100000, 999999);
//             }

//             $mainActivity = null;
//             if ($request->activity_id) {
//                 $mainActivity = Activity::where('rendom', $request->activity_id)->first();
//             }

//         }
        
//           $code = rand(100000, 999999);
//             while (Chat::where('rendom', $code)->exists()) {
//                 $code = rand(100000, 999999);
//             }

//             $chat = Chat::create([
//                 'sender_id' => $sender->id,
//                 'receiver_id' => $implodedReceiverIds,
//                 'message' => $request->message,
//                 'status' => 'sent',
//                 'rendom' => $code,
//                 'send_type' => $request->send_type,
//                 'chat_type' => $request->chat_type,
//                 'activity_id' => $request->activity_id ?? null,
//             ]);

//             $responses[] = [
//                 'receiver_rendom' => $receiverRendoms,
//                 'message' => $chat->message,
//                 'status' => 200,
//                 'rendom' => $chat->rendom,
//                 'sent_time' => Carbon::parse($chat->created_at)->diffForHumans(),
//             ];

//         return response()->json([
//             'message' => 'Group messages processed.',
//             'data' => $responses,
//             'receiver_ids' => $implodedReceiverIds, // returning here
//             'status' => 200,
//         ]);
//     }else{
//         $receiverRendoms = $request->receiver_rendom;
//         $receiver = User::where('rendom', $receiverRendoms)->first();

//         if (!$receiver) {
//             return response()->json([
//                 'message' => 'User Not Found',
//                 'data' => [],
//                 'status' => 200,
//             ]);
//         }

//     if ($receiver->id == $sender->id) {
//         return response()->json([
//             'message' => 'You cannot send message to yourself.',
//             'data' => [],
//             'status' => 400,
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
//         $allowedCount = $coinCategory ? $coinCategory->interest_messages_coin : 0;
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

//     $code = rand(100000, 999999);
//     while (Chat::where('rendom', $code)->exists()) {
//         $code = rand(100000, 999999);
//     }

//     $mainActivity = null;
//     if ($request->activity_id) {
//         $mainActivity = Activity::where('rendom', $request->activity_id)->first();
//     }

//     $chat = Chat::create([
//         'sender_id' => $sender->id,
//         'receiver_id' => $receiver->id,
//         'message' => $request->message,
//         'status' => 'sent',
//         'rendom' => $code,
//          'send_type' => $request->send_type,
//         'chat_type' => $request->chat_type,
//         'activity_id' => $request->activity_id ?? null,
//     ]);

//      $firebaseService = new FirebaseService(); // or inject via constructor

//       $title = $request->title ?? 'New Message';

//         $sent = $firebaseService->sendNotification(
//         $receiver->fcm_token,
//         $title,
//         $request->message,
//         [
//             'sender_rendom' => $sender->rendom,
//             'receiver_rendom' => $receiver->rendom,
//             'message' => $request->message,
//             'status' => 'sent',
//             'sent_time' => now()->diffForHumans(),
//             'screen' => 'Chat',
//         ]
//     );

//      if ($sent) {
//             Log::info("✅ Notification sent to user ID {$receiver->id}");
//         } else {
//             Log::warning("No FCM token for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
//             $responses[] = [
//                 'receiver_rendom' => $receiver->rendom,
//                 'message' => 'No FCM token available.',
//                 'status' => 400,
//             ];
//         }

//     return response()->json([
//         'message' => 'Message sent successfully.',
//         'data' => [
//             'sender_rendom' => $sender->rendom,
//             'receiver_rendom' => $receiver->rendom,
//             'message' => $chat->message,
//             'rendom' => $chat->rendom,
//             'chat_type' => $chat->chat_type,
//             'status' => $chat->status,
//             'sent_time' => Carbon::parse($chat->created_at)->diffForHumans(),
//         ],
//         'status' => 200,
//     ]);
//     }
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

    if ($request->send_type === 'group') {
         $receiverRendomsRaw = $request->receiver_rendom;

        $receiverRendoms = is_array($receiverRendomsRaw)
            ? $receiverRendomsRaw
            : json_decode($receiverRendomsRaw, true);

        $receivers = User::whereIn('rendom', $receiverRendoms)->get()->keyBy('rendom');
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

        if (empty($validReceiverIds)) {
            return response()->json([
                'message' => 'No valid receivers found.',
                'data' => $responses,
                'status' => 200,
            ]);
        }

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

        $implodedReceiverIds = implode(',', $validReceiverIds);

        $activity_detailes = Activity::where('id',$request->activity_id)->first();
        if(!$activity_detailes){
            return response()->json([
                    'message' => 'Activity Not Found',
                    'data' => [],
                    'status' => 203,
                ]);
        }
        $firebaseService = new FirebaseService();
       $title = $activity_detailes->title 
        ? 'New Message From ' . $activity_detailes->title . ' Group' 
        : 'New Group Message';

        foreach ($validReceiverIds as $receiverId) {
            $receiver = User::find($receiverId);

            if (!$receiver->fcm_token) {
                Log::warning("No FCM token for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
                $responses[] = [
                    'receiver_rendom' => $receiver->rendom,
                    'message' => 'No FCM token available.',
                    'status' => 400,
                ];
                continue;
            }

            $sent = $firebaseService->sendNotification(
                $receiver->fcm_token,
                $title,
                $request->message,
                [
                    'sender_rendom' => $sender->rendom,
                    'receiver_rendom' => $receiver->rendom,
                    'message' => $request->message,
                    'status' => 'sent',
                    'sent_time' => $now->diffForHumans(),
                    'screen' => 'Chat',
                ]
            );

            if ($sent) {
                Log::info("✅ Notification sent to user ID {$receiver->id}");
            } else {
                Log::warning("Notification failed for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
                $responses[] = [
                    'receiver_rendom' => $receiver->rendom,
                    'message' => 'Notification failed.',
                    'status' => 400,
                ];
            }
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
            'receiver_id' => $implodedReceiverIds,
            'message' => $request->message,
            'status' => 'sent',
            'rendom' => $code,
            'send_type' => $request->send_type,
            'chat_type' => $request->chat_type,
            'activity_id' => $request->activity_id ?? null,
        ]);

        $responses[] = [
            'receiver_rendom' => $receiverRendoms,
            'message' => $chat->message,
            'status' => 200,
            'rendom' => $chat->rendom,
            'sent_time' => Carbon::parse($chat->created_at)->diffForHumans(),
        ];

        return response()->json([
            'message' => 'Group messages processed.',
            'data' => $responses,
            'receiver_ids' => $implodedReceiverIds,
            'status' => 200,
        ]);
    } else {
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
            'activity_id' => $request->activity_id ?? null,
        ]);

        $firebaseService = new FirebaseService();
        $title = $request->title ?? 'New Message';

        if (!$receiver->fcm_token) {
            Log::warning("No FCM token for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
            $responses[] = [
                'receiver_rendom' => $receiver->rendom,
                'message' => 'No FCM token available.',
                'status' => 400,
            ];
        } else {
            $sent = $firebaseService->sendNotification(
                $receiver->fcm_token,
                $title,
                $request->message,
                [
                    'sender_rendom' => $sender->rendom,
                    'receiver_rendom' => $receiver->rendom,
                    'message' => $request->message,
                    'status' => 'sent',
                    'sent_time' => $now->diffForHumans(),
                    'screen' => 'Chat',
                ]
            );

            if ($sent) {
                Log::info("✅ Notification sent to user ID {$receiver->id}");
            } else {
                Log::warning("Notification failed for user ID: {$receiver->id}, rendom: {$receiver->rendom}");
                $responses[] = [
                    'receiver_rendom' => $receiver->rendom,
                    'message' => 'Notification failed.',
                    'status' => 400,
                ];
            }
        }

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

    if (!$authUser) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $receiverRendomss = $request->input('receiver_rendom');
    $send_type = $request->input('send_type');
    $activityId = $request->input('activity_id'); 

    // Convert input to array
    $receiverRendoms = is_array($receiverRendomss)
        ? $receiverRendomss
        : json_decode($receiverRendomss, true);

    // Get receiver users
    $receivers = User::whereIn('rendom', $receiverRendoms)->get();

    if ($receivers->isEmpty()) {
        return response()->json([
            'message' => 'No users found',
            'data' => [],
            'status' => 200,
        ]);
    }

    $receiverIdMap = $receivers->pluck('id', 'rendom');
    $receiverIds = $receiverIdMap->values()->toArray();
    $authId = $authUser->id;

    $allMessages = Chat::where(function ($query) use ($authId, $receiverIds, $send_type) {
    if ($send_type === 'single') {
        $query->where(function ($q) use ($authId, $receiverIds) {
            foreach ($receiverIds as $receiverId) {
                $q->orWhere(function ($q2) use ($authId, $receiverId) {
                    $q2->where('sender_id', $authId)
                        ->whereRaw("FIND_IN_SET(?, receiver_id)", [$receiverId]);
                });

                $q->orWhere(function ($q2) use ($authId, $receiverId) {
                    $q2->where('sender_id', $receiverId)
                        ->whereRaw("FIND_IN_SET(?, receiver_id)", [$authId]);
                });
            }
        });
    } else {
        // For group, keep as before
        $query->where('send_type', $send_type)
              ->where(function ($q) use ($authId) {
                  $q->where('sender_id', $authId)
                    ->orWhereRaw("FIND_IN_SET(?, receiver_id)", [$authId]);
              });
    }
    })
    ->when($send_type === 'group' && $activityId, function ($query) use ($activityId) {
        $query->where('activity_id', $activityId);
    })
    ->orderBy('created_at', 'asc')
    ->get();

    // Format messages
    $flatMessages = $allMessages->map(function ($message) use ($authId) {
        $sender = User::find($message->sender_id);
        $receiverIds = explode(',', $message->receiver_id);

        $otherUserId = $sender && $sender->id == $authId
            ? ($receiverIds[0] ?? null)
            : ($sender->id ?? null);

        $otherUser = $otherUserId ? User::find($otherUserId) : null;

        $profileImageUrl = null;
        if ($otherUser && $otherUser->profile_image) {
            $images = json_decode($otherUser->profile_image, true);
            if (is_array($images) && count($images)) {
                $profileImageUrl = url('') . '/uploads/app/profile_images/' . ltrim(reset($images), '/');
            }
        }

        return [
            'rendom' => $message->rendom,
            'chat_type' => $message->chat_type,
            'sender_rendom' => $sender->rendom ?? null,
            'receiver_rendom' => implode(',', $receiverIds),
            'profile_image' => $profileImageUrl,
            'message' => $message->message,
            'status' => $message->status,
            'send_type' => $message->send_type,
            'sent_time' => Carbon::parse($message->created_at)->diffForHumans(),
        ];
    });

    return response()->json([
        'message' => 'Messages fetched successfully.',
        'data' => $flatMessages->values(),
        'status' => 200,
    ]);
}


// public function getMessages(Request $request)
// {
//     $authUser = Auth::user();

//     if (!$authUser) {
//         return response()->json(['message' => 'User not authenticated'], 401);
//     }

//     $receiverRendomss = $request->input('receiver_rendom');
//     $send_type = $request->input('send_type');

//     // Convert input to array
//     $receiverRendoms = is_array($receiverRendomss)
//         ? $receiverRendomss
//         : json_decode($receiverRendomss, true);

//     // Get receiver users
//     $receivers = User::whereIn('rendom', $receiverRendoms)->get();

//     if ($receivers->isEmpty()) {
//         return response()->json([
//             'message' => 'No users found',
//             'data' => [],
//             'status' => 200,
//         ]);
//     }

//     $receiverIdMap = $receivers->pluck('id', 'rendom');
//     $receiverIds = $receiverIdMap->values()->toArray();
//     $authId = $authUser->id;

//     // 🛠️ Fix: handle sender or receiver (even inside comma-separated receiver_id)
//     $allMessages = Chat::where(function ($query) use ($authId, $receiverIds, $send_type) {
//         $query->where('send_type', $send_type)
//               ->where(function ($q) use ($authId, $receiverIds) {
//                   foreach ($receiverIds as $receiverId) {
//                       // Case 1: Auth user sent message to this receiver
//                       $q->orWhere(function ($q2) use ($authId, $receiverId) {
//                           $q2->where('sender_id', $authId)
//                               ->whereRaw("FIND_IN_SET(?, receiver_id)", [$receiverId]);
//                       });

//                       // Case 2: This receiver sent message to auth user
//                       $q->orWhere(function ($q2) use ($authId, $receiverId) {
//                           $q2->where('sender_id', $receiverId)
//                               ->whereRaw("FIND_IN_SET(?, receiver_id)", [$authId]);
//                       });

//                       // 🆕 Case 3: Auth user is inside receiver_id (for group chat)
//                       $q->orWhere(function ($q2) use ($authId) {
//                           $q2->whereRaw("FIND_IN_SET(?, receiver_id)", [$authId]);
//                       });
//                   }

//                   // 🆗 Case 4: Auth user is the sender (even without match to specific receiverIds)
//                   $q->orWhere('sender_id', $authId);
//               });
//     })->orderBy('created_at', 'asc')->get();

//     // Format messages
//     $flatMessages = $allMessages->map(function ($message) use ($authId) {
//         $sender = User::find($message->sender_id);
//         $receiverIds = explode(',', $message->receiver_id);

//         // Show the profile image of the **other** person
//         $otherUserId = $sender->id == $authId ? ($receiverIds[0] ?? null) : $sender->id;
//         $otherUser = User::find($otherUserId);

//         $profileImageUrl = null;
//         if ($otherUser && $otherUser->profile_image) {
//             $images = json_decode($otherUser->profile_image, true);
//             if (is_array($images) && count($images)) {
//                 $profileImageUrl = url('') . '/uploads/app/profile_images/' . ltrim(reset($images), '/');
//             }
//         }

//         return [
//             'rendom' => $message->rendom,
//             'chat_type' => $message->chat_type,
//             'sender_rendom' => $sender->rendom ?? null,
//             'receiver_rendom' => implode(',', $receiverIds),
//             'profile_image' => $profileImageUrl,
//             'message' => $message->message,
//             'status' => $message->status,
//             'send_type' => $message->send_type,
//             'sent_time' => Carbon::parse($message->created_at)->diffForHumans(),
//         ];
//     });

//     return response()->json([
//         'message' => 'Messages fetched successfully.',
//         'data' => $flatMessages->values(),
//         'status' => 200,
//     ]);
// }



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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnverifyUser;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Mail\OtpMail;
use App\Models\Activity;
use App\Models\Interest;
use App\Models\OtherInterest;
use App\Models\Vibes;
use App\Models\Expense;
use App\Models\LikeActivity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class InterestController extends Controller
{

    public function interest()
    {
        $data = Interest::where('status', 1)->get();

        $message = "Interest fetched successfully";
        $statusCode = 200; 

        // Hide unnecessary fields
        $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }


    public function userinterest()
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'User not authenticated.',
                'status' => 200
            ], 200);
        }

        $user = Auth::user();
 
        $interest_ids = json_decode($user->interest);
    
        if ($interest_ids === null) {
            return response()->json([
                'message' => 'Invalid interest data.',
                'status' => 400
            ], 400);
        }
  
        $data = Interest::whereIn('id', $interest_ids)->where('status', 1)->get();
  
        $message = "Interest fetched successfully";
        $statusCode = 200;
    
        $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }
    


    public function vibes()
    {
        $vibes = Vibes::where('status', 1)->get();

        $data = $vibes->map(function ($vibe) {
            return [
                'id' => $vibe->id,
                'name' => $vibe->name,
                'activity_id' => $vibe->activity_id,
                // 'icon' => $vibe->icon,
                'image' => $vibe->icon ? asset($vibe->icon) : null,
            ];
        });

        return response()->json([
            'message' => 'Vibes fetched successfully',
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function expense()
    {
        $data = Expense::where('status', 1)->get();
        $message = "Expense fetched successfully"; 
        $status = 200;
        $statusCode = 200; 

        $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);

        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data,
        ]);
    }



    public function addinterest(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ]);
    }

    $user = Auth::user();

    // Validate input
    $validator = Validator::make($request->all(), [
        'rendom' => 'required|exists:activity_table,rendom',
    ]);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0];
            break;
        }
        return response()->json(['message' => $errors, 'data' => [], 'status' => 201], 200);
    }

    // Get activity using rendom
    $activity = Activity::where('rendom', $request->rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
            'data' => [],
            'status' => 404,
        ]);
    }

    // Prevent user from liking their own activity
    if ($activity->user_id == $user->id) {
        return response()->json([
            'message' => 'You cannot add interest to your own activity.',
            'data' => [],
            'status' => 201,
        ]);
    }

    // Check existing interest (including confirm=5)
    $existingInterest = OtherInterest::where('user_id', $user->id)
                                    ->where('activity_id', $activity->id)
                                    ->first();

    if ($existingInterest) {
        if ($existingInterest->confirm == 5) {
            // User had removed interest before, now reactivate it
            $existingInterest->confirm = 0;
            $existingInterest->save();

            return response()->json([
                'message' => 'Interest re-activated successfully.',
                'status'  => 200,
                'data'    => [
                    'user_rendom'     => $user->rendom,
                    'activity_rendom' => $request->rendom,
                    'confirm'         => $existingInterest->confirm,
                ],
            ]);
        } else {
            return response()->json([
                'message' => 'Interest already added.',
                'data' => [],
                'status' => 200,
            ]);
        }
    }

    try {
        $otherInterest = OtherInterest::create([
            'user_id'     => $user->id,
            'activity_id' => $activity->id,
            'user_id_1'   => $activity->user_id,
            'confirm'     => 0,
        ]);

        return response()->json([
            'message' => 'Interest added successfully',
            'status'  => 200,
            'data'    => [
                'user_rendom'     => $user->rendom,
                'activity_rendom' => $request->rendom,
                'confirm'         => $otherInterest->confirm,
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to add interest. Please try again later.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}




public function like_activity(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'message' => 'Unauthorized',
            'status' => 401
        ]);
    }

    $request->validate([
        'activity_rendom' => 'required|exists:activity_table,rendom',
    ]);

    $activity = Activity::where('rendom', $request->activity_rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found',
            'status' => 404,
        ]);
    }

    $like = new LikeActivity();
    $like->user_id = $user->id;
    $like->activity_id = $activity->id;
    $like->status = 1;
    $like->save();

    return response()->json([
        'message' => 'Activity liked successfully',
        'status' => 200,
        'data' => [
            'user_id' => $user->id,
            'activity_id' => $activity->id,
        ]
    ]);
}





// public function remove_like_activity(Request $request)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return response()->json([
//             'message' => 'Unauthorized',
//             'status' => 401
//         ]);
//     }

//     $request->validate([
//         'activity_rendom' => 'required|exists:activity_table,rendom',
//     ]);

//     $activity = Activity::where('rendom', $request->activity_rendom)->first();

//     if (!$activity) {
//         return response()->json([
//             'message' => 'Activity not found',
//             'status' => 404,
//         ]);
//     }

//     $existingLike = LikeActivity::where('user_id', $user->id)
//                                 ->where('activity_id', $activity->id)
//                                 ->first();

//     if ($existingLike) {
//         $existingLike->status = 2;
//         $existingLike->save();

//         return response()->json([
//             'message' => 'Activity unliked successfully',
//             'status' => 200,
//             'data' => [
//                 'user_id' => $user->id,
//                 'activity_id' => $activity->id,
//                 'status' => 2
//             ]
//         ]);
//     } else {
//         return response()->json([
//             'message' => 'Like not found for this activity',
//             'status' => 404,
//         ]);
//     }
// }


//first
public function remove_like_activity(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'message' => 'Unauthorized',
            'status' => 401
        ]);
    }

    $request->validate([
        'activity_rendom' => 'required|exists:activity_table,rendom',
    ]);

    $activity = Activity::where('rendom', $request->activity_rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found',
            'status' => 404,
        ]);
    }

    $updated = LikeActivity::where('user_id', $user->id)
        ->where('activity_id', $activity->id)
        ->update(['status' => 2]);

    if ($updated) {
        return response()->json([
            'message' => 'Activity unliked successfully',
            'status' => 200,
            'data' => [
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => 2
            ]
        ]);
    } else {
        return response()->json([
            'message' => 'Like not found for this activity',
            'status' => 404,
        ]);
    }
}



public function addconfirms(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ]);
    }

    $user = Auth::user();

    // ✅ Validate request
    $validator = Validator::make($request->all(), [
        'rendom' => 'required|exists:activity_table,rendom',
        'users' => 'required|array|min:1',
        'users.*' => 'required|string',
    ]);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0];
            break;
        }
        return response()->json(['message' => $errors, 'data' => [], 'status' => 201], 200);
    }

    // ✅ Get activity
    $activity = Activity::where('rendom', $request->rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
            'data' => [],
            'status' => 404,
        ]);
    }

    // if ($activity->user_id == $user->id) {
    //     return response()->json([
    //         'message' => 'You cannot add interest to your own activity.',
    //         'data' => [],
    //         'status' => 201,
    //     ]);
    // }

    $userIds = User::whereIn('rendom', $request->users)->pluck('id')->toArray();

    if (empty($userIds)) {
        return response()->json([
            'message' => 'No valid users found from provided rendom values.',
            'data' => [],
            'status' => 201,
        ]);
    }

    // ✅ Step 2: Update OtherInterest records
    $updated = OtherInterest::where('activity_id', $activity->id)
        ->whereIn('user_id', $userIds)
        ->update(['confirm' => 6]);

    return response()->json([
        'message' => $updated > 0 ? 'Confirm status updated.' : 'No matching interests found to update.',
        'data' => [
            'activity_rendom' => $request->rendom,
            'user_ids_updated' => $userIds,
            'records_updated' => $updated,
        ],
        'status' => 200,
    ]);
}



public function getConfirmedUsers(Request $request)
{
    $validator = Validator::make($request->all(), [
        'rendom' => 'required|exists:activity_table,rendom',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first(),
            'data' => [],
            'status' => 422,
        ]);
    }

    $activity = Activity::where('rendom', $request->rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
            'data' => [],
            'status' => 404,
        ]);
    }

    $confirmedUsers = OtherInterest::with('user')
        ->where('activity_id', $activity->id)
        ->where('confirm', 6)
        ->get()
        ->map(function ($interest) {
            $user = $interest->user;
            $profileImageUrl = null;

         $profileImageUrl = null;
                if ($user->profile_image) {
                    $profileImages = json_decode($user->profile_image, true);

                    if (!empty($profileImages) && isset($profileImages[1])) {
                        $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
                    }
                }

            return [
                'user_id'          => $user->id ?? null,
                'user_name'        => $user->name ?? null,
                'user_email'       => $user->email ?? null,
                'confirm'          => $interest->confirm,
                'rendom'          => $user->rendom,
                'profile_image'    => $profileImageUrl,
            ];
        });

    return response()->json([
        'message' => 'Confirmed users fetched successfully.',
        'data'    => $confirmedUsers,
        'status'  => 200,
    ]);
}



public function removeinterest(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ]);
    }

    $user = Auth::user();

    // Validate input
    $validator = Validator::make($request->all(), [
        'rendom' => 'required|exists:activity_table,rendom',
    ]);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0];
            break;
        }
        return response()->json(['message' => $errors, 'data' => [], 'status' => 201], 200);
    }

    $activity = Activity::where('rendom', $request->rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
            'data' => [],
            'status' => 404,
        ]);
    }

    if ($activity->user_id == $user->id) {
        return response()->json([
            'message' => 'You cannot add interest to your own activity.',
            'data' => [],
            'status' => 201,
        ]);
    }

    $existingInterest = OtherInterest::where('user_id', $user->id)
                                    ->where('activity_id', $activity->id)
                                    ->first();

    if ($existingInterest) {
      
        $existingInterest->confirm = 5;
        $existingInterest->save();

        return response()->json([
            'message' => 'Interest updated to confirm = 5.',
            'data' => [
                'user_rendom'     => $user->rendom,
                'activity_rendom' => $request->rendom,
                'confirm'         => 5,
            ],
            'status' => 200,
        ]);
    }
}


    public function inviteinterest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ]);
        }
    
        $user = Auth::user();
    
        // Validate input
        $validator = Validator::make($request->all(), [
            'rendom'         => 'required|exists:activity_table,rendom',
            'user_rendoms'   => 'required|array|min:1',
            'user_rendoms.*' => 'required|string|exists:users,rendom',
        ]);
    
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                $errors[$field] = $messages[0];
                break;
            }
            return response()->json(['message' => $errors, 'data' => [], 'status' => 201], 200);
        }
    
        $activity = Activity::where('rendom', $request->rendom)->first();
    
        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found.',
                'data'    => [],
                'status'  => 404,
            ]);
        }
    
        $created = [];
        $skipped = [];
    
        foreach ($request->user_rendoms as $userRendom) {
            $invitedUser = User::where('rendom', $userRendom)->first();
    
            if (!$invitedUser) {
                $skipped[] = ['rendom' => $userRendom, 'reason' => 'User not found'];
                continue;
            }
    
            if ($invitedUser->id == $activity->user_id) {
                $skipped[] = ['rendom' => $userRendom, 'reason' => 'User is the activity owner'];
                continue;
            }
    
            $existingInterest = OtherInterest::where('user_id', $invitedUser->id)
                                             ->where('activity_id', $activity->id)
                                             ->first();
    
            if ($existingInterest) {
                $skipped[] = ['rendom' => $userRendom, 'reason' => 'Interest already exists'];
                continue;
            }
    
            OtherInterest::create([
                'user_id'     => $invitedUser->id,
                'activity_id' => $activity->id,
                'confirm'     => 0,
            ]);
    
            $created[] = $userRendom;
        }
    
        return response()->json([
            'message' => 'Interest processing completed.',
            'status'  => 200,
            'data'    => [
                'activity_rendom' => $activity->rendom,
                'created'         => $created,
                'skipped'         => $skipped,
            ],
        ]);
    }
    
    


    // public function addinterest(Request $request)
    // {
    //     if (!Auth::check()) {  
    //         return response()->json([
    //             'message' => 'Unauthorized. Please log in.',
    //         ]);
    //     }

    //     $user = Auth::user();
    //     $activity_id = Activity::where('rendom',$request->rendom)->first();
    //     // return $activity_id;

    //     $validator = Validator::make($request->all(), [
    //         'rendom' => 'required|exists:activity_table',
    //     ]);
        
    //     if ($validator->fails()) {
    //         $errors = [];
    //         foreach ($validator->errors()->getMessages() as $field => $messages) {
    //             $errors[$field] = $messages[0];
    //             break; 
    //         }
    //         return response()->json(['message' => $errors,'data' => [],'status' => 201], 200);
    //     }
    //     $existingInterest = OtherInterest::where('user_id', $user->id)
    //                                     ->where('activity_id', $activity_id->id)
    //                                     ->first();

    //     if ($existingInterest) {
    //         return response()->json([
    //             'message' => 'Interest already added.',
    //             'data' => [],
    //             'status' => 200,
    //         ]);
    //     }
    
    
    //     try {
    //         $otherInterest = OtherInterest::create([
    //             'user_id' => $user->id,
    //             'activity_id' => $activity_id->id,
    //             'confirm' => 0,
    //         ]);

    //         return response()->json([
    //             'message' => 'Interest added successfully',
    //             'status' => 200,
    //             'data' => [
    //                 'user_rendom' => $user->rendom,
    //                 'activity_rendom' => $request->rendom,
    //                 'confirm' => $otherInterest->confirm,  
    //             ],
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Failed to add interest. Please try again later.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    // public function getuserinterest(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'message' => 'Unauthorized. Please log in.',
    //             'status' => 201,
    //         ]);
    //     }

    //     $user = Auth::user();

    //     $request->validate([
    //         'rendom' => 'required',
    //     ]);

    //     $activity = Activity::where('rendom',$request->rendom)->first();

    //     if (!$activity) {
    //         return response()->json([
    //             'message' => 'Activity not found.',
    //             'data' => [],
    //             'status' => 201,
    //         ],200);
    //     }

    //     $howMany = $activity->how_many;

    //     $interests = OtherInterest::where('activity_id', $activity->id)
    //                             ->where('confirm', 0)
    //                             ->get();

    //     $confirm = OtherInterest::where('activity_id', $activity->id)
    //                             ->where('confirm', 1)
    //                             ->take($howMany)
    //                             ->get();

    //     if ($interests->isEmpty() && $confirm->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No interests found for this activity.',
    //         ]);
    //     }

    //     $interestsArray = $interests->map(function ($interest) {
    //         $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
    //                                         ->where('confirm', 0)->count(); 
    //         $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
    //                                                 ->where('confirm', 1)->count(); 
    //         $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
    //                                             ->count(); 

    //         $profileImages = json_decode($interest->user->profile_image ?? '[]', true);
    //         $profileImageUrl = !empty($profileImages) ? asset('uploads/app/profile_images/' . $profileImages[1] ?? '') : '';

    //         $activity_rendom = Activity::where('id',$interest->activity_id)->first();
    //         $user_rendom = User::where('id',$interest->user_id)->first();

    //         return [
    //             // 'id' => $interest->id,
    //             'user' => $interest->user->name ?? '',
    //             'user_rendom' => $user_rendom->rendom,
    //             'user_profile' => $profileImageUrl,
    //             'activity_rendom' => $activity_rendom->rendom,
    //             'confirm' => $interest->confirm,
    //             'ghosted' => $userInterestsCount,
    //             'attended' => $userInterestsconfirmCount,
    //             'created' => $userInterestsactivityCount,
    //         ];
    //     });

    //     $confirmArray = $confirm->map(function ($interest) {
    //         $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
    //                                         ->where('confirm', 0)->count(); 
    //         $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
    //                                                 ->where('confirm', 1)->count(); 
    //         $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
    //                                             ->count(); 

    //         $profileImages = json_decode($interest->user->profile_image ?? '[]', true);
    //         $profileImageUrl = !empty($profileImages) ? asset('uploads/app/profile_images/' . $profileImages[1] ?? '') : '';

    //         $activity_rendom = Activity::where('id',$interest->activity_id)->first();
    //         $user_rendom = User::where('id',$interest->user_id)->first();
    //         return [
    //             // 'id' => $interest->id,
    //             'user' => $interest->user->name ?? '',
    //             'user_rendom' => $user_rendom->rendom,
    //             'user_profile' => $profileImageUrl,
    //             'activity_rendom' => $activity_rendom->rendom,
    //             'confirm' => $interest->confirm,
    //             'ghosted' => $userInterestsCount,
    //             'attended' => $userInterestsconfirmCount,
    //             'created' => $userInterestsactivityCount,
    //         ];
    //     });

    //     return response()->json([
    //         'message' => 'User interests fetched successfully',
    //         'status' => 200,
    //         'data' => [
    //             'interests' => $interestsArray,
    //             'confirmed' => $confirmArray
    //         ],
    //     ]);
    // }


    public function getuserinterest(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
            'status' => 401,
        ]);
    }

    $user = Auth::user();

    $request->validate([
        'rendom' => 'required',
    ]);

    $activity = Activity::where('rendom', $request->rendom)->first();

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
            'data' => [],
            'status' => 404,
        ]);
    }

    $howMany = $activity->how_many;

    $interests = OtherInterest::with('user') // eager load user
        ->where('activity_id', $activity->id)
        ->where('confirm', 0)
        ->get();

    $confirm = OtherInterest::with('user')
        ->where('activity_id', $activity->id)
        ->where('confirm', 1)
        ->take($howMany)
        ->get();

    if ($interests->isEmpty() && $confirm->isEmpty()) {
        return response()->json([
            'message' => 'No interests found for this activity.',
            'status' => 200,
            'data' => [
                'interests' => [],
                'confirmed' => [],
            ],
        ]);
    }

    $mapUserInterest = function ($interest) {
        $user = $interest->user;

        $ghosted = OtherInterest::where('user_id', $user->id)
            ->where('confirm', 0)
            ->count();

        $attended = OtherInterest::where('user_id', $user->id)
            ->where('confirm', 1)
            ->count();

        $created = Activity::where('user_id', $user->id)->count();

        $profileImages = json_decode($user->profile_image ?? '[]', true);
        $profileImageUrl = isset($profileImages[1])
            ? asset('uploads/app/profile_images/' . $profileImages[1])
            : '';

        return [
            'user' => $user->name ?? '',
            'user_rendom' => $user->rendom ?? '',
            'user_profile' => $profileImageUrl,
            'activity_rendom' => $interest->activity->rendom ?? '',
            'confirm' => $interest->confirm,
            'ghosted' => $ghosted,
            'attended' => $attended,
            'created' => $created,
        ];
    };

    $interestsArray = $interests->map($mapUserInterest);
    $confirmArray = $confirm->map($mapUserInterest);

    return response()->json([
        'message' => 'User interests fetched successfully',
        'status' => 200,
        'data' => [
            'interests' => $interestsArray,
            'confirmed' => $confirmArray,
        ],
    ]);
}


    public function confirmuserinterest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
                'status' => 200,
            ]);
        }
    
        $user = Auth::user();
    
        $request->validate([
            'rendom' => 'required',
        ]);

        $activity_rendom = Activity::where('rendom',$request->rendom)->first();

        if (!$activity_rendom) {
            return response()->json([
                'message' => 'Data Not Found',
                'data' => [],
                'status' => 201,
        ], 200);
        }
    
        $interests = OtherInterest::where('activity_id', $activity_rendom->id)
                                   ->where('confirm', 1)
                                   ->get();
    
        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No confirmed interests found for this activity.',
                'data' => [],
                'status' => 200,
            ]); 
        }

        $interestsArray = $interests->map(function ($interest) {
            $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
        ->where('confirm', 0)->count(); 
        $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
        ->where('confirm', 1)->count(); 
        $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
        ->count(); 

        $activity_rendoms = Activity::where('id',$interest->activity_id)->first();
            $user_rendom = User::where('id',$interest->user_id)->first();
            return [
                // 'id' => $interest->id,
                'user' => $interest->user->name ?? '',
                'user_rendom' => $user_rendom->rendom,
                'activity_rendom' => $activity_rendoms->rendom,
                'confirm' => $interest->confirm,
                'ghosted' => $userInterestsCount,
                'attended' => $userInterestsconfirmCount,
                'created' => $userInterestsactivityCount,
            ];
        });

        return response()->json([
            'message' => 'Confirmed user interests fetched successfully',
            'status' => 200,
            'data' => $interestsArray,
        ], 200); 
    }

    

    public function confirm_user_interest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
                'status' => 201,
            ]);
        }
    
        $user = Auth::user();
    
        $request->validate([
            'activity_rendom' => 'required',
            'user_rendom' => 'required',
            'confirm' => 'required|boolean', 
        ]);
    
        $activity = Activity::where('rendom',$request->activity_rendom)->first();
        $user_rendom = User::where('rendom',$request->user_rendom)->first();
    
        if (!$activity) {
            return response()->json([
               'message' => 'Activity not found',
                'data'=>[],
                'status'=>201
            ],200);
        }
    
        $howMany = $activity->how_many;

        $confirmedCount = OtherInterest::where('activity_id', $activity->id)
                                       ->where('confirm', 1)
                                       ->count();
    

        if ($confirmedCount >= $howMany) {
            return response()->json([
                'message' => 'Maximum confirmations reached for this activity.',
                'status' => 201,
            ]);
        }

        $interest = OtherInterest::where('activity_id', $activity->id)
                                 ->where('user_id', $user_rendom->id) 
                                 ->first();
    
        if (!$interest) {
            return response()->json([
                'message' => 'No interest found for this activity.',
                'status' => 201,
            ]);
        }

        $interest->confirm = $request->confirm;
        $interest->save();
    
        $activity_data = Activity::where('id',$interest->activity_id)->first();
        $user_rendom_data = User::where('id',$interest->user_id)->first();

        return response()->json([
            'message' => 'Interest confirmation updated successfully.',
            'data' => [
                'activity_rendom' => $activity_data->rendom,
                'user' => $interest->user->name ?? '',
                'user_rendom' => $user_rendom_data->rendom,
                'confirm' => $interest->confirm,
            ],
        ]);
    }


}

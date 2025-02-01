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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class InterestController extends Controller
{

    public function interest()
{
    $data = Interest::where('status', 1)->get();

    // Include icon URL for each interest item (assuming each interest has an 'icon' field)
    // $data->each(function ($item) {
    //     $item->icon_url = asset('uploads/app/int_images/' . $item->icon);
    // });

    $message = "Interest fetched successfully";
    $statusCode = 200; 

    // Hide unnecessary fields
    $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);

    return response()->json([
        'message' => $message,
        'data' => $data,
    ], $statusCode);
}

    public function vibes()
    {
        $data = Vibes::where('status', 1)->get();
        $message = "Vibes fetched successfully"; 
        $status = 200;
        $statusCode = 200; 

        $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);


        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data,
        ], $statusCode);
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
        ], $statusCode);
    }


    public function addinterest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $user = Auth::user();

        $request->validate([
            'activity_id' => 'required|exists:activity_table,id',
        ]);
        $existingInterest = OtherInterest::where('user_id', $user->id)
                                        ->where('activity_id', $request->activity_id)
                                        ->first();

        if ($existingInterest) {
            return response()->json([
                'message' => 'Interest already added.',
                'status' => 200,
            ], 200);
        }
    
    
        try {
            $otherInterest = OtherInterest::create([
                'user_id' => $user->id,
                'activity_id' => $request->activity_id,
                'confirm' => 0,
            ]);

            return response()->json([
                'message' => 'Interest added successfully',
                'status' => 200,
                'data' => [
                    'user_id' => $user->id,
                    'activity_id' => $request->activity_id,
                    'confirm' => $otherInterest->confirm,  
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add interest. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function getuserinterest(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'message' => 'Unauthorized. Please log in.',
    //         ], 401);
    //     }
    
    //     $user = Auth::user();
    
    //     $request->validate([
    //         'activity_id' => 'required|exists:activity_table,id',
    //     ]);
    
    //     $interests = OtherInterest::where('activity_id', $request->activity_id)
    //                                ->where('confirm', 0)
    //                                ->get();
    
    //     if ($interests->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No interests found for this activity.',
    //         ], 404);
    //     }

    //     $interestsArray = $interests->map(function ($interest) {
    //         return [
    //             'id' => $interest->id,
    //             'user' => $interest->user->name ?? '',
    //             'user_id' => $interest->user_id,
    //             'activity_id' => $interest->activity_id,
    //             'confirm' => $interest->confirm,
    //         ];
    //     });
    
    //     return response()->json([
    //         'message' => 'User interests fetched successfully',
    //         'data' => $interestsArray,
    //     ]);
    // }

    public function getuserinterest(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    $user = Auth::user();

    $request->validate([
        'activity_id' => 'required|exists:activity_table,id',
    ]);

    // Get the Activity record to check the how_many field
    $activity = Activity::find($request->activity_id);

    if (!$activity) {
        return response()->json([
            'message' => 'Activity not found.',
        ], 404);
    }

    $howMany = $activity->how_many;

    // Get the interests where confirm is 0 for the given activity
    $interests = OtherInterest::where('activity_id', $request->activity_id)
                               ->where('confirm', 0)
                               ->get();

    // Get the interests where confirm is 1 for the given activity
    $confirm = OtherInterest::where('activity_id', $request->activity_id)
                             ->where('confirm', 1)
                             ->take($howMany) // Limit the number of confirmed interests based on how_many
                             ->get();

    if ($interests->isEmpty() && $confirm->isEmpty()) {
        return response()->json([
            'message' => 'No interests found for this activity.',
        ], 404);
    }

    // Map the pending interests and include the count of interests for each user
    $interestsArray = $interests->map(function ($interest) {
        $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
                                           ->where('confirm', 0)->count(); 
        $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
                                                  ->where('confirm', 1)->count(); 
        $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
                                              ->count(); 

        $profileImages = json_decode($interest->user->profile_image ?? '[]', true);
        $profileImageUrl = !empty($profileImages) ? asset('uploads/app/profile_images/' . $profileImages[1] ?? '') : '';

        return [
            'id' => $interest->id,
            'user' => $interest->user->name ?? '',
            'user_id' => $interest->user_id,
            'user_profile' => $profileImageUrl,
            'activity_id' => $interest->activity_id,
            'confirm' => $interest->confirm,
            'ghosted' => $userInterestsCount,
            'attended' => $userInterestsconfirmCount,
            'created' => $userInterestsactivityCount,
        ];
    });

    // Map confirmed interests similarly, but limit based on how_many
    $confirmArray = $confirm->map(function ($interest) {
        $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
                                           ->where('confirm', 0)->count(); 
        $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
                                                  ->where('confirm', 1)->count(); 
        $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
                                              ->count(); 

        $profileImages = json_decode($interest->user->profile_image ?? '[]', true);
        $profileImageUrl = !empty($profileImages) ? asset('uploads/app/profile_images/' . $profileImages[1] ?? '') : '';

        return [
            'id' => $interest->id,
            'user' => $interest->user->name ?? '',
            'user_id' => $interest->user_id,
            'user_profile' => $profileImageUrl,
            'activity_id' => $interest->activity_id,
            'confirm' => $interest->confirm,
            'ghosted' => $userInterestsCount,
            'attended' => $userInterestsconfirmCount,
            'created' => $userInterestsactivityCount,
        ];
    });

    return response()->json([
        'message' => 'User interests fetched successfully',
        'status' => 200,
        'data' => [
            'interests' => $interestsArray,
            'confirmed' => $confirmArray
        ],
    ]);
}

    public function confirmuserinterest(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }
    
        $user = Auth::user();
    
        $request->validate([
            'activity_id' => 'required',
        ]);
    
        $interests = OtherInterest::where('activity_id', $request->activity_id)
                                   ->where('confirm', 1)
                                   ->get();
    
        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No confirmed interests found for this activity.',
                'data' => [],
            ], 404); 
        }

        $interestsArray = $interests->map(function ($interest) {
            $userInterestsCount = OtherInterest::where('user_id', $interest->user_id)
        ->where('confirm', 0)->count(); 
        $userInterestsconfirmCount = OtherInterest::where('user_id', $interest->user_id)
        ->where('confirm', 1)->count(); 
        $userInterestsactivityCount = Activity::where('user_id', $interest->user_id)
        ->count(); 

            return [
                'id' => $interest->id,
                'user' => $interest->user->name ?? '',
                'user_id' => $interest->user_id,
                'activity_id' => $interest->activity_id,
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
            ], 401);
        }
    
        $user = Auth::user();
    
        $request->validate([
            'activity_id' => 'required|exists:activity_table,id',
            'user_id' => 'required',
            'confirm' => 'required|boolean', 
        ]);
    
        $activity = Activity::find($request->activity_id);
    
        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found.',
            ], 404);
        }
    
        $howMany = $activity->how_many;

        $confirmedCount = OtherInterest::where('activity_id', $request->activity_id)
                                       ->where('confirm', 1)
                                       ->count();
    

        if ($confirmedCount >= $howMany) {
            return response()->json([
                'message' => 'Maximum confirmations reached for this activity.',
            ], 400);
        }

        $interest = OtherInterest::where('activity_id', $request->activity_id)
                                 ->where('user_id', $request->user_id) 
                                 ->first();
    
        if (!$interest) {
            return response()->json([
                'message' => 'No interest found for this activity.',
            ], 404);
        }

        $interest->confirm = $request->confirm;
        $interest->save();
    
        return response()->json([
            'message' => 'Interest confirmation updated successfully.',
            'data' => [
                'activity_id' => $interest->activity_id,
                'user' => $interest->user->name ?? '',
                'user_id' => $interest->user_id,
                'confirm' => $interest->confirm,
            ],
        ]);
    }


}

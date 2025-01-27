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
use App\Models\Interest;
use App\Models\Vibes;
use App\Models\Activity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class ActivityController extends Controller
{
    public function activitystore(Request $request)
{
    // Ensure the user is authenticated
    if (Auth::check()) {
        // User is authenticated, continue with the method
    } else {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    $user = Auth::user();
    // return $user; 

    $validator = Validator::make($request->all(), [
        'where_to' => 'required|string|max:255',
        'when_time' => 'required|string|max:255',
        'how_many' => 'required|integer',
        'start_time' => 'required',
        'end_time' => 'required',
        'interests_id' => 'required',
        'expense_id' => 'required',
        'other_activity' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $activity = Activity::create([
        'where_to' => $request->where_to,
        'when_time' => $request->when_time,
        'how_many' => $request->how_many,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'interests_id' => $request->interests_id,
        'expense_id' => $request->expense_id,
        'other_activity' => $request->other_activity,
        'status' => 1,
        'description' => $request->description,
        'user_id' => $user->id,
    ]);

    return response()->json([
        'message' => 'Activity created successfully',
        'data' => $activity,
    ], 201);
}


    
    public function activitys(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $interestField = $user->interest;
        $interestFieldDecoded = json_decode($interestField, true);

        if (!is_array($interestFieldDecoded)) {
            return response()->json(['message' => 'Invalid interest data'], 400);
        }

        $interestIds = [];
        foreach ($interestFieldDecoded as $item) {
            $interestIds = array_merge($interestIds, explode(',', $item));
        }
        $interestIds = array_map('trim', $interestIds);
        $interests = Interest::whereIn('id', $interestIds)->get();

        return response()->json([
            'message' => 'User interests fetched successfully',
            'data' => $interests,
        ]);
    }

    public function findMatchingUsers(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $interestField = $user->interest;

        $interestFieldDecoded = json_decode($interestField, true);
    
        if (!is_array($interestFieldDecoded)) {
            return response()->json(['message' => 'Invalid interest data'], 400);
        }

        $interestIds = [];
        foreach ($interestFieldDecoded as $item) {
            $interestIds = array_merge($interestIds, explode(',', $item));
        }

        $interestIds = array_map('trim', $interestIds);
 
        $matchingUsers = User::where(function ($query) use ($interestIds) {
            foreach ($interestIds as $interestId) {
                $query->orWhere('interest', 'like', "%$interestId%");
            }
        })
        ->where('id', '!=', $user->id) 
        ->get();
    
        if ($matchingUsers->isEmpty()) {
            return response()->json(['message' => 'No matching users found'], 404);
        }
    
        $usersWithInterests = [];

        foreach ($matchingUsers as $matchingUser) {
            $userInterestsField = $matchingUser->interest;

            $userInterestsDecoded = json_decode($userInterestsField, true);
    
            if (is_array($userInterestsDecoded)) {
                $userInterestsIds = [];
                foreach ($userInterestsDecoded as $item) {
                    $userInterestsIds = array_merge($userInterestsIds, explode(',', $item));
                }
    
                $userInterestsIds = array_map('trim', $userInterestsIds);

                $userInterests = Interest::whereIn('id', $userInterestsIds)->get();

                $usersWithInterests[] = [
                    'user' => $matchingUser,
                    'interests' => $userInterests
                ];
            }
        }
        return response()->json([
            'message' => 'Matching users found successfully',
            'data' => $usersWithInterests,
        ]);
    }


    public function findMatchingactivity(Request $request)
    {
        $user = Auth::user(); 
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        
        $interestField = $user->interest; 
        $interestFieldDecoded = json_decode($interestField, true);
        
        if (!is_array($interestFieldDecoded)) {
            return response()->json(['message' => 'Invalid interest data'], 400);
        }
        
        $interestIds = [];
        foreach ($interestFieldDecoded as $item) {
            $interestIds = array_merge($interestIds, explode(',', $item));
        }
        
        $interestIds = array_map('trim', $interestIds);

        $matchingActivities = Activity::whereIn('interests_id', $interestIds)
                                        ->where('user_id', '!=', $user->id) 
                                        ->get();
        
        if ($matchingActivities->isEmpty()) {
            return response()->json(['message' => 'No matching activities found'], 404);
        }

        $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
            $userDetails = User::find($activity->user_id);
            
            if ($userDetails) {
                $activity->user_details = $userDetails;
            }
            return $activity;
        });
        return response()->json([
            'message' => 'Matching activities found successfully',
            'data' => $activitiesWithUserDetails,
        ]);
    }


}

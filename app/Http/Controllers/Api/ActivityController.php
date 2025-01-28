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
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class ActivityController extends Controller
{
    public function activitystore(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    $user = Auth::user();

    // Validation for incoming request
    $validator = Validator::make($request->all(), [
        'title' => 'required',
        'location' => 'required',
        'how_many' => 'required|integer',
        'start_time' => 'required|date_format:H:i:s',
        'end_time' => 'required|date_format:H:i:s',
        'interests_id' => 'required',
        'expense_id' => 'required',
        'description' => 'required',
        'other_activity' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'amount' => 'nullable|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Parse start_time and end_time, assuming the current date if only time is provided
    try {
        $startTime = $this->parseTimeToDate($request->start_time);
        $endTime = $this->parseTimeToDate($request->end_time);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Invalid time format. Expected H:i:s.',
            'error' => $e->getMessage(),
        ], 422);
    }

    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->move(public_path('images/activities'), $image->getClientOriginalName());
        $imagePath = asset('images/activities/' . $image->getClientOriginalName());
    }

    $activity = Activity::create([
        'where_to' => $request->where_to,
        'title' => $request->title,
        'location' => $request->location,
        'when_time' => $request->when_time,
        'how_many' => $request->how_many,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'interests_id' => implode(',', (array)$request->interests_id), 
        'expense_id' => implode(',', (array)$request->expense_id),
        'status' => 1,
        'description' => $request->description,
        'other_activity' => $request->other_activity,
        'user_id' => $user->id,
        'image' => $imagePath,
        'amount' => $request->amount, 
    ]);
 
    return response()->json([
        'message' => 'Activity created successfully',
        'data' => $activity,
    ], 200);
}

private function parseTimeToDate($time)
{
    $todayDate = Carbon::today()->format('Y-m-d');
    $dateTime = $todayDate . ' ' . $time;
    try {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTime)->format('Y-m-d H:i:s');
    } catch (\Exception $e) {
        throw new \Exception('Invalid time format.');
    }
}


public function useractivitys(Request $request)
{
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }
    
    // Get the current time and today's date in the 'Asia/Kolkata' timezone
    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');
    
    // Fetch activities for the authenticated user
    $activities = Activity::where('user_id', $user->id)
        ->whereDate('when_time', $todayDate) 
        ->where('end_time', '<', $currentTime)
        ->get();
    
    // If no activities are found
    if ($activities->isEmpty()) {
        return response()->json(['message' => 'No upcoming activities found'], 200);
    }
    
    $activitiesArray = $activities->map(function ($activity) {
        return [
            'id' => $activity->id,
            'title' => $activity->title,
            'description' => $activity->description,
            'location' => $activity->location,
            'when_time' => $activity->when_time,
            'start_time' => $activity->start_time,
            'end_time' => $activity->end_time,
            'how_many' => $activity->how_many,
            'interests_id' => $activity->interests_id,  
            'expense_id' => $activity->expense_id,     
            'status' => $activity->status,
        ];
    });

    return response()->json([
        'message' => 'User activities fetched successfully',
        'data' => $activitiesArray,
    ]);
}


public function getActivitydetailes(Request $request)
{
    // Ensure the user is authenticated
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }
    
    // Validate that 'activity_id' is provided and exists in the Activity table
    $request->validate([
        'activity_id' => 'required',
    ]);

    // Fetch the activity based on the provided activity_id
    $activity = Activity::where('id', $request->activity_id)
                        ->where('user_id', $user->id)  // Ensure that the activity belongs to the authenticated user
                        ->first();
    
    if (!$activity) {
        return response()->json(['message' => 'Activity not found or you do not have permission to view it'], 404);
    }

    // Format the activity data
    $activityData = [
        'id' => $activity->id,
        'title' => $activity->title,
        'description' => $activity->description,
        'location' => $activity->location,
        'when_time' => $activity->when_time,
        'start_time' => $activity->start_time,
        'end_time' => $activity->end_time,
        'how_many' => $activity->how_many,
        'interests_id' => $activity->interests_id,  
        'expense_id' => $activity->expense_id,     
        'status' => $activity->status,
    ];

    return response()->json([
        'message' => 'Activity fetched successfully',
        'data' => $activityData,
    ]);
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

            $interestsArray = $interests->map(function ($interest) {

                $bgColor = '#' . substr(md5($interest->id), 0, 6); 

                return [
                    'id' => $interest->id,
                    'name' => $interest->name,
                    'icon' => $interest->icon,
                    'status' => $interest->status,
                    'desc' => $interest->desc,
                    'bg_color' => $bgColor, 
                ];
            });

            return response()->json([
                'message' => 'User interests fetched successfully',
                'data' => $interestsArray,
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


    // public function findMatchingactivity(Request $request)
    // {
    //     $user = Auth::user(); 
        
    //     if (!$user) {
    //         return response()->json(['message' => 'User not authenticated'], 401);
    //     }
        
    //     $interestField = $user->interest; 
    //     $interestFieldDecoded = json_decode($interestField, true);
        
    //     if (!is_array($interestFieldDecoded)) {
    //         return response()->json(['message' => 'Invalid interest data'], 400);
    //     }
        
    //     $interestIds = [];
    //     foreach ($interestFieldDecoded as $item) {
    //         $interestIds = array_merge($interestIds, explode(',', $item));
    //     }
        
    //     $interestIds = array_map('trim', $interestIds);

    //     $matchingActivities = Activity::whereIn('interests_id', $interestIds)
    //                                     ->where('user_id', '!=', $user->id) 
    //                                     ->get();
        
    //     if ($matchingActivities->isEmpty()) {
    //         return response()->json(['message' => 'No matching activities found'], 404);
    //     }

    //     $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
    //         $userDetails = User::find($activity->user_id);
            
    //         if ($userDetails) {
    //             $activity->user_details = $userDetails;
    //         }
    //         return $activity;
    //     });
    //     return response()->json([
    //         'message' => 'Matching activities found successfully',
    //         'data' => $activitiesWithUserDetails,
    //     ]);
    // }


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

    // Fetch matching activities
    $matchingActivities = Activity::whereIn('interests_id', $interestIds)
                                    ->where('user_id', '!=', $user->id) 
                                    ->get();

    if ($matchingActivities->isEmpty()) {
        return response()->json(['message' => 'No matching activities found'], 404);
    }

    // Prepare activities with user details and random colors
    $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
        // Generate a unique color for each activity based on its id
        $bgColor = '#' . substr(md5($activity->id), 0, 6);

        // Fetch user details for the activity
        $userDetails = User::find($activity->user_id);

        // Add user details and color to the activity
        if ($userDetails) {
            $activity->user_details = $userDetails;
        }
        $activity->bg_color = $bgColor;  // Adding bg_color to the activity
        
        return $activity;
    });

    return response()->json([
        'message' => 'Matching activities found successfully',
        'data' => $activitiesWithUserDetails,
    ]);
}

public function filteractivity(Request $request)
{
    $location = $request->input('location');
    $when_time = $request->input('when_time');
    $start_time = $request->input('start_time');
    $end_time = $request->input('end_time');
    $expense_id = $request->input('expense_id');
    $interests_id = $request->input('interests_id');

    // Start building the query on the Activity model
    $query = Activity::query();

    // Apply filters if parameters are provided
    $filterApplied = false;

    if ($location) {
        $query->where('location', 'like', '%' . $location . '%');
        $filterApplied = true;
    }

    if ($when_time) {
        $query->where('when_time', $when_time);
        $filterApplied = true;
    }

    if ($start_time) {
        $query->where('start_time', '>=', $start_time);
        $filterApplied = true;
    }

    if ($end_time) {
        $query->where('end_time', '<=', $end_time);
        $filterApplied = true;
    }

    if ($expense_id) {
        $query->where('expense_id', $expense_id);
        $filterApplied = true;
    }

    if ($interests_id) {
        $query->where('interests_id', $interests_id);
        $filterApplied = true;
    }

    // If no filter is applied, you can return a message or empty array
    if (!$filterApplied) {
        return response()->json(['message' => 'No filters applied, returning all activities'], 200);
    }

    // Get the filtered results
    $activities = $query->get();

    return response()->json($activities);
}

}

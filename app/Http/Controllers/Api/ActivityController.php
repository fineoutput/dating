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
            'vibe_id' => 'nullable',
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
    
        // Create the activity record
        $activity = Activity::create([
            'where_to' => $request->where_to,
            'title' => $request->title,
            'location' => $request->location,
            'when_time' => $request->when_time,
            'how_many' => $request->how_many,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'vibe_id' => $request->vibe_id,
            'interests_id' => implode(',', (array)$request->interests_id), 
            'expense_id' => implode(',', (array)$request->expense_id),
            'status' => 1,
            'description' => $request->description,
            'other_activity' => $request->other_activity,
            'user_id' => $user->id,
            'image' => $imagePath,
            'amount' => $request->amount, 
        ]);

        $activityData = $activity->toArray();
        unset($activityData['created_at'], $activityData['updated_at']);
    
        return response()->json([
            'message' => 'Activity created successfully',
            'status' => 200,
            'data' => $activityData, 
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
    
    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');
    
    $activities = Activity::where('user_id', $user->id)
        ->whereDate('when_time', $todayDate)
        ->where('end_time', '>', $currentTime)
        ->get();

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
            // 'vibe_id' => $vibeName,
            'vibe_name' => $activity->vibe->name ?? '',
            'expense_id' => $activity->expense_id,     
            'status' => $activity->status,
        ];
    });

    return response()->json([
        'message' => 'User activities fetched successfully',
        'status' => 200,
        'data' => $activitiesArray,
    ]);
}


public function getActivitydetailes(Request $request)
{

    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $request->validate([
        'activity_id' => 'required',
    ]);

    $activity = Activity::where('id', $request->activity_id)->first();
    
    if (!$activity) {
        return response()->json(['message' => 'Activity not found or you do not have permission to view it'], 404);
    }

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
        'vibe_name' => $activity->vibe->name ?? '',  
        'expense_id' => $activity->expense_id,     
        'status' => $activity->status,
    ];

    return response()->json([
        'message' => 'Activity fetched successfully',
        'status' => 200,
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
                'status' => 200,
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
            'status' => 200,
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

        $matchingActivities = Activity::whereIn('interests_id', $interestIds)
                                    ->where('user_id', '!=', $user->id) 
                                    ->get();

    if ($matchingActivities->isEmpty()) {
        return response()->json(['message' => 'No matching activities found'], 404);
    }

    $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));
        
        $lightenFactor = 0.6; 
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);
        
        // Convert back to hex format
        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);
    
        $userDetails = User::find($activity->user_id);
    
        if ($userDetails) {
            $profileImages = json_decode($userDetails->profile_image, true);
            $profileImageUrl = isset($profileImages[1]) ? url('uploads/app/profile_images/' . $profileImages[1]) : null;
    
            // Merging user details directly in the main array
            $userData = [
                'id' => $userDetails->id,
                'name' => $userDetails->name,
                'profile_image' => $profileImageUrl, 
                'state' => $userDetails->state,
                'city' => $userDetails->city,
                'time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'), 
            ];
        }
    
        $imageUrl = $activity->image ? url('images/activities/' . $activity->image) : null;
    
        $activity->bg_color = $bgColor;

        return [
            'id' => $activity->id,
            // 'user_id' => $activity->user_id,
            'title' => $activity->title,
            'location' => $activity->location,    
            // 'image' => $imageUrl,
            'bg_color' => $activity->bg_color,
            'vibe_name' => $activity->vibe->name ?? '',
            'vibe_icon' => $activity->vibe->icon ?? '',
            // 'user_id' => $userDetails->id,
            'user_name' => $userDetails->name,
            'user_profile_image' => $profileImageUrl,
            // 'user_state' => $userDetails->state,
            // 'user_city' => $userDetails->city,
            'user_time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'), 
        ];
    });
        return response()->json([
            'message' => 'Matching activities found successfully',
            'status' => 200,
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

            $query = Activity::query();

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
            if (!$filterApplied) {
                return response()->json(['message' => 'No filters applied, returning all activities'], 200);
            }
            $activities = $query->get();
            $activities->makeHidden(['created_at', 'updated_at', 'deleted_at']);
            return response()->json($activities->toArray());
        }


        
        public function vibeactivitycount(){

        }




}

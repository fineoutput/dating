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
use App\Models\Expense;
use App\Models\Interest;
use App\Models\Vibes;
use App\Models\Activity;
use App\Models\ActivitySubscription;
use App\Models\ActivityTemp;
use App\Models\Chat;
use App\Models\Cupid;
use App\Models\SlideLike;
use App\Models\OtherInterest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class ActivityController extends Controller
{
    // public function activitystore(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'message' => 'Unauthorized. Please log in.',
    //         ], 401);
    //     }
    
    //     $user = Auth::user();
    
    //     // Validation for incoming request
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required',
    //         'location' => 'required',
    //         'how_many' => 'required|integer',
    //         'start_time' => 'nullable',
    //         'end_time' => 'required',
    //         'when_time' => 'required',
    //         'interests_id' => 'nullable',
    //         'vibe_id' => 'nullable',
    //         'expense_id' => 'required',
    //         'description' => 'required',
    //         'other_activity' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'amount' => 'required|numeric',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }
    
    //     // Parse start_time and end_time, assuming the current date if only time is provided
    //     // try {
           
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'message' => 'Invalid time format. Expected H:i:s.',
    //     //         'error' => $e->getMessage(),
    //     //     ], 422);
    //     // }
    //     // $startTime = $this->parseTimeToDate($request->start_time);
    //     // $endTime = $this->parseTimeToDate($request->end_time);
    
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imagePath = $image->move(public_path('images/activities'), $image->getClientOriginalName());
    //         $imagePath = asset('images/activities/' . $image->getClientOriginalName());
    //     }
    
    //     // Create the activity record
    //     $activity = ActivityTemp::create([
    //         'where_to' => $request->where_to,
    //         'title' => $request->title,
    //         'location' => $request->location,
    //         'when_time' => $request->when_time,
    //         'how_many' => $request->how_many,
    //         'start_time' => $request->start_time,
    //         'end_time' => $request->end_time,
    //         'vibe_id' => $request->vibe_id,
    //         'interests_id' => implode(',', (array)$request->interests_id), 
    //         'expense_id' => implode(',', (array)$request->expense_id),
    //         'status' => 1,
    //         'description' => $request->description,
    //         'other_activity' => $request->other_activity,
    //         'user_id' => $user->id,
    //         'image' => $imagePath,
    //         'amount' => $request->amount, 
    //     ]);

    //     $activityData = $activity->toArray();
    //     unset($activityData['created_at'], $activityData['updated_at']);
    
    //     return response()->json([
    //         'message' => 'Activity created successfully',
    //         'status' => 200,
    //         'data' => $activityData, 
    //     ], 200);
    // }

    // public function activitystore(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'message' => 'Unauthorized. Please log in.',
    //         ], 401);
    //     }
    
    //     $user = Auth::user();
    
    //     // Validation for incoming request
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'nullable',
    //         'location' => 'nullable',
    //         'how_many' => 'nullable|integer',
    //         'start_time' => 'nullable',
    //         'end_time' => 'nullable',
    //         'when_time' => 'nullable',
    //         'interests_id' => 'nullable', // It should be an array for multiple values
    //         'vibe_id' => 'nullable',
    //         'expense_id' => 'nullable', // It should be an array for multiple values
    //         'description' => 'nullable',
    //         'other_activity' => 'nullable|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'amount' => 'nullable|numeric',
    //         'activity_id' => 'nullable|exists:activity_temp_table,id', // for update
    //         'update_status' => 'nullable|in:update,final', // to handle status
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }
    
    //     // If activity_id exists, we are updating the activity
    //     if ($request->has('activity_id') && $request->update_status == 'update') {
    //         // Find the activity in ActivityTemp to update
    //         $activityTemp = ActivityTemp::find($request->activity_id);
    
    //         if (!$activityTemp) {
    //             return response()->json(['message' => 'Activity not found'], 404);
    //         }
    
    //         // Update activity details
    //         $activityTemp->user_id = $user->id;  // Always update user_id
    //         $activityTemp->where_to = $request->where_to ?? $activityTemp->where_to;
    //         $activityTemp->when_time = $request->when_time ?? $activityTemp->when_time;
    //         $activityTemp->how_many = $request->how_many ?? $activityTemp->how_many;
    //         $activityTemp->start_time = $request->start_time ?? $activityTemp->start_time;
    //         $activityTemp->end_time = $request->end_time ?? $activityTemp->end_time;
    //         $activityTemp->interests_id = isset($request->interests_id) ? implode(',', (array)$request->interests_id): $activityTemp->interests_id;  
    //         $activityTemp->vibe_id = $request->vibe_id ?? $activityTemp->vibe_id;
    //         $activityTemp->expense_id = isset($request->expense_id) ? implode(',', (array)$request->expense_id) : $activityTemp->expense_id;  // Handling multiple expense_id
    //         $activityTemp->other_activity = $request->other_activity ?? $activityTemp->other_activity;
    //         $activityTemp->status = 1;  // Keep status as 1 unless changed in final status
    //         $activityTemp->title = $request->title ?? $activityTemp->title;
    //         $activityTemp->description = $request->description ?? $activityTemp->description;
    //         $activityTemp->location = $request->location ?? $activityTemp->location;
    
    //         if ($request->hasFile('image')) {
    //             $image = $request->file('image');
    //             $imagePath = $image->move(public_path('images/activities'), $image->getClientOriginalName());
    //             $activityTemp->image = asset('images/activities/' . $image->getClientOriginalName());
    //         }
    
    //         $activityTemp->amount = $request->amount ?? $activityTemp->amount;
    
    //         // Save updated data to ActivityTemp
    //         $activityTemp->save();
    
    //         return response()->json([
    //             'message' => 'Activity updated in ActivityTemp',
    //             'status' => 200,
    //             'data' => $activityTemp,
    //         ], 200);
    //     }
    
    //     // If update_status is final, move data to Activity table
    //     if ($request->has('update_status') && $request->update_status == 'final') {
    //         // Find the activity in ActivityTemp to finalize
    //         $activityTemp = ActivityTemp::find($request->activity_id);
    
    //         if (!$activityTemp) {
    //             return response()->json(['message' => 'Activity not found'], 404);
    //         }
    
    //         // Create the final activity in the Activity table
    //         $activity = Activity::create([
    //             'user_id' => $activityTemp->user_id,
    //             'where_to' => $activityTemp->where_to,
    //             'when_time' => $activityTemp->when_time,
    //             'how_many' => $activityTemp->how_many,
    //             'start_time' => $activityTemp->start_time,
    //             'end_time' => $activityTemp->end_time,
    //             'interests_id' => $activityTemp->interests_id,
    //             'vibe_id' => $activityTemp->vibe_id,
    //             'expense_id' => $activityTemp->expense_id,
    //             'status' => $activityTemp->status,
    //             'title' => $activityTemp->title,
    //             'description' => $activityTemp->description,
    //             'location' => $activityTemp->location,
    //             'other_activity' => $activityTemp->other_activity,
    //             'image' => $activityTemp->image,
    //             'amount' => $activityTemp->amount,
    //         ]);
    
    //         // Optionally, delete the temporary activity after finalizing
    //         $activityTemp->delete();
    
    //         return response()->json([
    //             'message' => 'Activity moved to Activity table successfully',
    //             'status' => 200,
    //             'data' => $activity,
    //         ], 200);
    //     }
    
    //     // If activity_id does not exist and it's not an update or final status, create a new activity in ActivityTemp
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imagePath = $image->move(public_path('images/activities'), $image->getClientOriginalName());
    //         $imagePath = asset('images/activities/' . $image->getClientOriginalName());
    //     }
    
    //     // Create a new activity in ActivityTemp
    //     $activityTemp = ActivityTemp::create([
    //         'user_id' => $user->id,
    //         'where_to' => $request->where_to,
    //         'title' => $request->title,
    //         'location' => $request->location,
    //         'when_time' => $request->when_time,
    //         'how_many' => $request->how_many,
    //         'start_time' => $request->start_time,
    //         'end_time' => $request->end_time,
    //         'interests_id' => isset($request->interests_id) ? implode(',', $request->interests_id) : null, // Handling multiple interests_id
    //         'vibe_id' => $request->vibe_id,
    //         'expense_id' => isset($request->expense_id) ? implode(',', $request->expense_id) : null, // Handling multiple expense_id
    //         'status' => 1,
    //         'description' => $request->description,
    //         'other_activity' => $request->other_activity,
    //         'image' => $imagePath,
    //         'amount' => $request->amount,
    //     ]);
    
    //     $activityData = $activityTemp->toArray();
    //     unset($activityData['created_at'], $activityData['updated_at']);
    
    //     return response()->json([
    //         'message' => 'Activity created successfully in ActivityTemp',
    //         'status' => 200,
    //         'data' => $activityData,
    //     ], 200);
    // }
    

    public function activitystore(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }
    
        $user = Auth::user();

        if ($user->subscription == 0) {

            $currentDate = now();
            $sevenDaysAgo = now()->subWeek();

            $activityCount = Activity::where('user_id', $user->id)
                ->whereBetween('created_at', [$sevenDaysAgo, $currentDate])
                ->count();
                $activitysubscriptioncount =  ActivitySubscription::orderBy('id','DESC')->first();
  
            if ($activityCount >= $activitysubscriptioncount->activity_count) {
                return response()->json([
                    'message' => 'You can only create up to activities per week due to your subscription.',
                ], 400);
            }
    
            $validator = Validator::make($request->all(), [
                'title' => 'nullable',
                'location' => 'nullable',
                'rendom' => 'nullable',
                'how_many' => 'nullable|integer',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'when_time' => 'nullable',
                // 'interests_id' => 'nullable',
                // 'interests_id.*' => 'integer',
                'vibe_id' => 'nullable',
                'expense_id' => 'nullable',  
                'description' => 'nullable',
                'other_activity' => 'nullable|string',
                'image' => 'nullable',
                'amount' => 'nullable|numeric',
                'activity_id' => 'nullable|exists:activity_temp_table,id', 
                'update_status' => 'nullable|in:update,final', 
            ]);
        } else {
            // When subscription is not 0, there is no limit to activities or interests.
            $validator = Validator::make($request->all(), [
                'title' => 'nullable',
                'rendom' => 'nullable',
                'location' => 'nullable',
                'how_many' => 'nullable|integer',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'when_time' => 'nullable',
                // 'interests_id' => 'nullable',
                'vibe_id' => 'nullable',
                'expense_id' => 'nullable',  
                'description' => 'nullable',
                'other_activity' => 'nullable|string',
                'image' => 'nullable',
                'amount' => 'nullable|numeric',
                'activity_id' => 'nullable|exists:activity_temp_table,id', 
                'update_status' => 'nullable|in:update,final', 
            ]);
        }
    
        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'status' => 201,
            ], 422);
        }
    
        // If activity_id exists, we are updating the activity
        if ($request->has('rendom') && $request->update_status == 'update') {
            $activityTemp = ActivityTemp::where('rendom',$request->rendom)->first();
    
            if (!$activityTemp) {
                return response()->json([
                'message' => 'Activity not found',
                'data'=>[],
                'status'=>201
            ], 200);
            }
    
            // Update activity details
            $activityTemp->user_id = $user->id;  
            $activityTemp->where_to = $request->where_to ?? $activityTemp->where_to;
            $activityTemp->when_time = $request->when_time ?? $activityTemp->when_time;
            $activityTemp->how_many = $request->how_many ?? $activityTemp->how_many;
            $activityTemp->start_time = $request->start_time ?? $activityTemp->start_time;
            $activityTemp->end_time = $request->end_time ?? $activityTemp->end_time;
            $activityTemp->interests_id = isset($user->interest) ? implode(',', (array)$user->interest) : $user->interest;
            $activityTemp->vibe_id = $request->vibe_id ?? $activityTemp->vibe_id;
            $activityTemp->expense_id = isset($request->expense_id) ? implode(',', (array)$request->expense_id) : $activityTemp->expense_id;
            $activityTemp->other_activity = $request->other_activity ?? $activityTemp->other_activity;
            $activityTemp->status = 2;  // Keep status as 1 unless changed in final status
            $activityTemp->title = $request->title ?? $activityTemp->title;
            $activityTemp->description = $request->description ?? $activityTemp->description;
            $activityTemp->location = $request->location ?? $activityTemp->location;
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/activities'), $imageName);
                $activityTemp->image = 'images/activities/' . $imageName;
            }
            
    
            $activityTemp->amount = $request->amount ?? $activityTemp->amount;
    
            // Save updated data to ActivityTemp
            $activityTemp->save();
            $activityTemp->makeHidden(['created_at', 'updated_at', 'deleted_at','id','user_id']);
            return response()->json([
                'message' => 'Activity updated in ActivityTemp',
                'status' => 200,
                'data' => $activityTemp,
            ], 200);
        }
    
        // If update_status is final, move data to Activity table
        if ($request->has('rendom') && $request->update_status == 'final') {
            // Find the activity in ActivityTemp to finalize
            $activityTemp = ActivityTemp::where('rendom',$request->rendom)->first();
    
            if (!$activityTemp) {
                return response()->json([
                    'message' => 'Activity not found',
                    'data'=>[],
                    'status'=>201
            ], 200);
            }
    
            // Create the final activity in the Activity table
            // $randomNumber = rand(100000, 999999);
            // return  $activityTemp->rendom;

            $activity = Activity::create([
                'user_id' => $activityTemp->user_id,
                'where_to' => $activityTemp->where_to,
                'when_time' => $activityTemp->when_time,
                'how_many' => $activityTemp->how_many,
                'start_time' => $activityTemp->start_time,
                'end_time' => $activityTemp->end_time,
                'interests_id' => $activityTemp->interests_id,
                'vibe_id' => $activityTemp->vibe_id,
                'expense_id' => $activityTemp->expense_id,
                'status' => $activityTemp->status,
                'title' => $activityTemp->title,
                'description' => $activityTemp->description,
                'location' => $activityTemp->location,
                'other_activity' => $activityTemp->other_activity,
                'image' => $activityTemp->image,
                'amount' => $activityTemp->amount,
                'rendom' => $activityTemp->rendom,
            ]); 
    
            // Optionally, delete the temporary activity after finalizing
            $activityTemp->delete();
            $activity->makeHidden(['created_at', 'updated_at', 'deleted_at','user_id','id']);
    
            return response()->json([
                'message' => 'Activity moved to Activity table successfully',
                'status' => 200,
                'data' => $activity,
            ], 200);
        }
    
        // If activity_id does not exist and it's not an update or final status, create a new activity in ActivityTemp
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->move(public_path('images/activities'), $image->getClientOriginalName());
            $imagePath = asset('images/activities/' . $image->getClientOriginalName());
        }
    
        // Create a new activity in ActivityTemp
        // $randomNumber = rand(100000, 999999);
        do {
            $randomNumber = rand(100000, 999999);
        } while (ActivityTemp::where('rendom', $randomNumber)->exists());
        
        $activityTemp = ActivityTemp::create([
            'user_id' => $user->id,
            'rendom' => $randomNumber,
            'where_to' => $request->where_to,
            'title' => $request->title,
            'location' => $request->location,
            'when_time' => $request->when_time,
            'how_many' => $request->how_many,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'interests_id' => isset($user->interests_id) ? implode(',', (array)$user->interests_id) : null,  // Handling multiple interests_id
            'vibe_id' => $request->vibe_id,
            'expense_id' => isset($request->expense_id) ? implode(',', (array)$request->expense_id) : null, // Handling multiple expense_id
            'status' => 1,
            'description' => $request->description,
            'other_activity' => $request->other_activity,
            'image' => $imagePath,
            'amount' => $request->amount,
        ]);
    
        $activityData = $activityTemp->toArray();
        unset($activityData['created_at'], $activityData['updated_at'],$activityData['id'],$activityData['user_id']);
    
        return response()->json([
            'message' => 'Activity created successfully in ActivityTemp',
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

    $currentTime = Carbon::now('Asia/Kolkata');  // Current time in Asia/Kolkata
    $todayDate = Carbon::today('Asia/Kolkata');  // Today's date in Asia/Kolkata

    $activities = Activity::orderBy('id', 'DESC')
        ->where('user_id', $user->id)
        ->where('status', 2) 
        ->whereDate('when_time', '>=', $todayDate->format('Y-m-d')) 
        ->get();
    
    $filteredActivities = [];
    
    foreach ($activities as $activity) {

        $whenTime = Carbon::parse($activity->when_time)->setTimezone('Asia/Kolkata');

        $endTime = Carbon::createFromFormat('h:i A', $activity->end_time)->setTimezone('Asia/Kolkata'); 

        $combinedDateTime = $whenTime->copy()->setTimeFromTimeString($endTime->toTimeString());

        if ($combinedDateTime >= $currentTime) {

            $filteredActivities[] = $activity;
        }
    }

    // return $filteredActivities;
    
// $currentTime = Carbon::now('Asia/Kolkata');  // Current time in Asia/Kolkata
// $todayDate = Carbon::today('Asia/Kolkata');
// // return $currentTime->format('H:i:s');

// // Fetch activities
// $activities = Activity::orderBy('id', 'DESC')
//     ->where('user_id', $user->id)
//     ->where('status', 2) 
//     ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))  
//     ->whereTime('end_time', '>=', $currentTime->format('H:i:s'))  
//     ->get();

// return $activities;


    if ($activities->isEmpty()) {
        return response()->json([
            'message' => 'No upcoming activities found',
            'status'=>200,
            'data'=>[],
        ], 200);
    }

    // Process the profile image URL
    $profileImageUrl = null;
    if ($user->profile_image) {
        $profileImages = json_decode($user->profile_image, true);

        if (!empty($profileImages) && isset($profileImages[1])) {
            $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
        }
    }

    // Process each activity directly and add bg_color
    $activitiesData = [];
    foreach ($activities as $activity) {
        // Generate background color based on activity ID
        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $lightenFactor = 0.5;
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);

        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

        $activitiesData[] = [
            'rendom' => $activity->rendom,
            'when_time' => $activity->when_time,
            'end_time' => $activity->end_time,
            'title' => $activity->title,
            'location' => $activity->location,
            'bg_color' => $bgColor,
            'how_many' => $activity->how_many,
            'vibe_name' => $activity->vibe->name ?? '',
            'vibe_icon' => $activity->vibe->icon ?? '',
            'user_name' => $user->name,
            'user_profile_image' => $profileImageUrl,
            'user_time' => \Carbon\Carbon::parse($activity->created_at)->format('d-F H:i'),
            'status' => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
        ];
    }

    // Return the response with all activities included
    return response()->json([
        'message' => 'User activities fetched successfully',
        'status' => 200,
        'data' => $activitiesData, // Return the activities directly without nesting them inside a separate array
    ]);
}





// public function getActivitydetailes(Request $request)
// {
//     $user = Auth::user();
    
//     if (!$user) {
//         return response()->json(['message' => 'User not authenticated'], 401);
//     }

//     $request->validate([
//         'rendom' => 'required',
//     ]);


//     $activity = Activity::where('rendom', $request->rendom)->first();
//     $activityUSER = Activity::where('rendom', $request->rendom)->where('user_id',$user->id)->first();

//     if (!$activity) {
//         return response()->json([
//             'message' => 'Activity Not Found',
//             'data' => [],
//             'status' => 201,
//         ], 200);
//     }
//     $interestcount =OtherInterest::where('activity_id', $activity->id)->count();

//     // Handle the case where expense_id is stored as a JSON string
//     $expenseIds = json_decode($activity->expense_id, true); // Decodes the string to an array
//     $firstExpenseName = null;

//     if (is_array($expenseIds) && count($expenseIds) > 0) {
//         // Retrieve the first expense ID from the array
//         $firstExpenseId = $expenseIds[0];

//         // Fetch the corresponding expense name from the database (assuming you have an Expense model)
//         $firstExpense = Expense::where('id', $firstExpenseId)->first();

//         if ($firstExpense) {
//             $firstExpenseName = $firstExpense->name;
//         }
//     }

   
//     $activityInterest = OtherInterest::where('activity_id', $activity->id)
//     ->where('confirm', 1)
//     ->with('user') // Eager load the 'user' relationship
//     ->get();

// // Map through the results and add the user profile image
// $activityInterestWithProfileImage = $activityInterest->map(function($interest) {
//     if ($interest->user && $interest->user->profile_image) {
//         // Decode the JSON string stored in the profile_image field
//         $profileImages = json_decode($interest->user->profile_image, true);
        
//         // Get the first image or you can select the desired one based on your logic
//         $imageUrl = isset($profileImages[1]) ? asset('uploads/app/profile_images/' . $profileImages[1]) : null;
        
//         // Return only the image URL
//         return $imageUrl;
//     }
//     // If no user or profile image exists, return null
//     return null;
// });

// // Filter out any null results (where there is no profile image)
// $activityInterestWithProfileImage = $activityInterestWithProfileImage->filter(function($value) {
//     return $value !== null;
// });

//     if (!$activity) {
//         return response()->json(['message' => 'Activity not found or you do not have permission to view it'], 404);
//     }

//     $profileImages = json_decode($activity->user->profile_image, true);
//                 $profileImageUrl = null;
    
//                 if (!empty($profileImages) && isset($profileImages[1])) {
//                     $profileImageUrl = asset('uploads/app/profile_images/' . $profileImages[1]);
//                 }
                
//     $time = strtotime($activity->when_time);
    
//     if(!empty($activityUSER)){
//         $like_user = true;
//     }else{
//         $like_user = false;
//     }
//     $activityData = [
//         // 'id' => $activity->id,
//         'user_name' => $activity->user->name ?? '',
//         'profile_image' => $profileImageUrl,
//         'title' => $activity->title,
//         'description' => $activity->description,
//         'location' => $activity->location,
//         'when_time' => date('h.i a', $time),
//         'how_many' => $activity->how_many,
//         'interestCount' => $interestcount,
//         'vibe_name' => $activity->vibe->name ?? '',  
//         'vibe_icon' => $activity->vibe->icon ?? '',  
//         'like_user' => $like_user ?? '',  
//         // 'expense_id' => $activity->expense_id,
//         'expense_name' => $firstExpenseName, 
//         'status' => $activity->status,
//         'attendees' => [ 
//             'attendees_count' => $activityInterestWithProfileImage->count(),
//             'attendees_images' => $activityInterestWithProfileImage->values()
//         ]
//     ];

//     return response()->json([
//         'message' => 'Activity fetched successfully',
//         'status' => 200,
//         'data' => $activityData,
//     ]);
// }


public function getActivitydetailes(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $request->validate([
        'rendom' => 'required',
    ]);

    // 🔹 1. Main activity from rendom
    $mainActivity = Activity::with('user', 'vibe')->where('rendom', $request->rendom)->first();

    if (!$mainActivity) {
        return response()->json([
            'message' => 'Activity Not Found',
            'data' => [],
            'status' => 201,
        ], 200);
    }

    // Expense name
    $expenseIds = json_decode($mainActivity->expense_id, true);
    $firstExpenseName = null;
    if (is_array($expenseIds) && count($expenseIds) > 0) {
        $firstExpense = Expense::find($expenseIds[0]);
        $firstExpenseName = $firstExpense->name ?? null;
    }

    // Profile image
    $profileImages = json_decode($mainActivity->user->profile_image ?? '[]', true);
    $profileImageUrl = isset($profileImages[1]) ? asset('uploads/app/profile_images/' . $profileImages[1]) : null;

    // Logged-in user is creator?
    $like_user = $mainActivity->user_id === $user->id;

    // Count confirmed users
    $interestCount = OtherInterest::where('activity_id', $mainActivity->id)->count();

    $mainActivityData = [
        'user_name' => $mainActivity->user->name ?? '',
        'rendom' => $mainActivity->rendom ?? '',
        'profile_image' => $profileImageUrl,
        'title' => $mainActivity->title,
        'description' => $mainActivity->description,
        'location' => $mainActivity->location,
        'when_time' => date('h:i a', strtotime($mainActivity->when_time)),
        'how_many' => $mainActivity->how_many,
        'interestCount' => $interestCount,
        'vibe_name' => $mainActivity->vibe->name ?? '',
        'vibe_icon' => $mainActivity->vibe->icon ?? '',
        'like_user' => $like_user,
        'expense_name' => $firstExpenseName,
        'status' => $mainActivity->status,
    ];

    $attendees = OtherInterest::where('activity_id', $mainActivity->id)
        ->where('confirm', 1)
        ->with('user')
        ->get();

    $attendeeList = $attendees->map(function ($a) {
        $images = json_decode($a->user->profile_image ?? '[]', true);
        $img = isset($images[1]) ? asset('uploads/app/profile_images/' . $images[1]) : null;

        return [
            'user_id' => $a->user->id ?? null,
            'user_name' => $a->user->name ?? '',
            'profile_image' => $img,
        ];
    });

    $allActivities = Activity::with('user', 'vibe')
        ->orderBy('id', 'desc')
        ->where('user_id', '!=', $user->id)
        ->get()
        ->map(function ($act) {
            $images = json_decode($act->user->profile_image ?? '[]', true);
            $img = isset($images[1]) ? asset('uploads/app/profile_images/' . $images[1]) : null;

            $expenseIds = json_decode($act->expense_id, true);
            $expense = is_array($expenseIds) && count($expenseIds) > 0
                ? Expense::find($expenseIds[0])->name ?? null
                : null;

            return [
                'user_name' => $act->user->name ?? '',
                'rendom' => $act->rendom ?? '',
                'profile_image' => $img,
                'title' => $act->title,
                'description' => $act->description,
                'location' => $act->location,
                'when_time' => date('h:i a', strtotime($act->when_time)),
                'how_many' => $act->how_many,
                'vibe_name' => $act->vibe->name ?? '',
                'vibe_icon' => $act->vibe->icon ?? '',
                'expense_name' => $expense,
                'status' => $act->status,
            ];
        });

    $mainActivityData['attendees_count'] = $attendeeList->count();
    $mainActivityData['attendees'] = $attendeeList->values();

    // Merge into one flat data array
    $mergedData = array_merge([$mainActivityData], $allActivities->toArray());

    // 🔢 Add serial_number to each entry
    $mergedData = array_map(function ($item, $index) {
        $item['serial_number'] = $index + 1;
        return $item;
    }, $mergedData, array_keys($mergedData));

    return response()->json([
        'message' => 'Activity details fetched',
        'status' => 200,
        'data' => $mergedData,
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
                    // 'id' => $interest->id,
                    'name' => $interest->name,
                    // 'icon' => $interest->icon,
                    'icon' => asset('uploads/app/int_images/' . $interest->icon),
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
            return response()->json(['message' => 'Invalid interest data','status'=>201], 400);
        }

        $interestIds = [];
        foreach ($interestFieldDecoded as $item) {
            $interestIds = array_merge($interestIds, explode(',', $item));
        }


    $interestIds = array_map('trim', $interestIds);

    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');

    $matchingActivities = Activity::orderBy('id','DESC')->where('user_id', '!=', $user->id)
    ->where('status', 2)
    ->whereDate('when_time', '>=', $todayDate)
    ->where(function ($query) use ($interestIds) {
        foreach ($interestIds as $id) {
            $query->orWhere('interests_id', 'LIKE', '%"'.$id.'"%');
        }
    })->where(function ($query) use ($todayDate, $currentTime) {
        $query->where(function ($subQuery) use ($todayDate, $currentTime) {
   
            $endTime = Carbon::createFromFormat('H:i:s', '08:28:00')->setDate($todayDate->year, $todayDate->month, $todayDate->day);
 
            $subQuery->where('end_time', '>=', $endTime);
        });

        $query->where('when_time', '>=', $currentTime);  
    })
    ->get();
   
        // $matchingActivities = Activity::whereIn('interests_id', $interestIds)
        //                             ->where('user_id', '!=', $user->id)
        //                             ->get();

    if ($matchingActivities->isEmpty()) {
        return response()->json([
            'message' => 'No matching activities found',
            'status'=>200,
            'data'=>[],
        ], 200);
    }

    $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
        $hash = md5($activity->id);
$r = hexdec(substr($hash, 0, 2));
$g = hexdec(substr($hash, 2, 2));
$b = hexdec(substr($hash, 4, 2));

$lightenFactor = 0.5;  // Adjust the lightening factor to 50%
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
            // 'id' => $activity->id,
            // 'user_id' => $activity->user_id,
            'title' => $activity->title,
            'rendom' => $activity->rendom,
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


    // public function interestactivity(Request $request)
    // {
    //     $user = Auth::user(); 
    
    //     if (!$user) {
    //         return response()->json(['message' => 'User not authenticated'], 401);
    //     }

    //     $interestIds = OtherInterest::where('user_id', $user->id)->get();
    //     $activityIds = $interestIds->pluck('activity_id'); 
    //     $matchingActivities = Activity::whereIn('id', $activityIds)->where('status', 2)->get();
    
    //     if ($matchingActivities->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No matching activities found',
    //             'status' => 200,
    //             'data' => [],
    //         ], 200);
    //     }
    
    //     $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
    //         $hash = md5($activity->id);
    //         $r = hexdec(substr($hash, 0, 2));
    //         $g = hexdec(substr($hash, 2, 2));
    //         $b = hexdec(substr($hash, 4, 2));
    
    //         $lightenFactor = 0.5;  
    //         $r = round($r + (255 - $r) * $lightenFactor);
    //         $g = round($g + (255 - $g) * $lightenFactor);
    //         $b = round($b + (255 - $b) * $lightenFactor);

    //         $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);
    
    //         $userd = Auth::id();
    //         $userDetails = User::find($userd);
    
    //         $profileImageUrl = null;
    
    //         if ($userDetails) {
    //             $profileImages = json_decode($userDetails->profile_image, true);
    //             $profileImageUrl = isset($profileImages[1]) ? url('uploads/app/profile_images/' . $profileImages[1]) : null;
    
    //             $userData = [
    //                 'id' => $userDetails->id,
    //                 'name' => $userDetails->name,
    //                 'profile_image' => $profileImageUrl, 
    //                 'state' => $userDetails->state,
    //                 'city' => $userDetails->city,
    //                 'time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'), 
    //             ];
    //         }
    
    //         $imageUrl = $activity->image ? url('images/activities/' . $activity->image) : null;
    
    //         $activity->bg_color = $bgColor;
    
    //         return [
    //             'title' => $activity->title,
    //             'rendom' => $activity->rendom,
    //             'location' => $activity->location,    
    //             'bg_color' => $activity->bg_color,
    //             'vibe_name' => $activity->vibe->name ?? '',
    //             'vibe_icon' => $activity->vibe->icon ?? '',
    //             'user_name' => $userDetails->name,
    //             'user_profile_image' => $profileImageUrl, 
    //             'user_time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'),
    //         ];
    //     });
    
    //     return response()->json([
    //         'message' => 'Matching activities found successfully',
    //         'status' => 200,
    //         'data' => $activitiesWithUserDetails,
    //     ]);
    // }


    public function interestactivity(Request $request)
{
    $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $interestIds = OtherInterest::where('user_id', $user->id)->get();
    $activityIds = $interestIds->pluck('activity_id'); 
    $matchingActivities = Activity::whereIn('id', $activityIds)->where('status', 2)->get();

    if ($matchingActivities->isEmpty()) {
        return response()->json([
            'message' => 'No matching activities found',
            'status' => 200,
            'data' => [],
        ]);
    }

    $activitiesWithUserDetails = $matchingActivities->map(function ($activity) {
        // Generate background color based on activity ID hash
        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $lightenFactor = 0.5;  
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);

        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

        // 🔄 Get creator user details (not current user)
        $userDetails = User::find($activity->user_id);

        $profileImageUrl = null;
        $userData = [];

        if ($userDetails) {
            $profileImages = json_decode($userDetails->profile_image ?? '[]', true);
            $profileImageUrl = isset($profileImages[1]) ? url('uploads/app/profile_images/' . $profileImages[1]) : null;

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

        return [
            'title' => $activity->title,
            'rendom' => $activity->rendom,
            'location' => $activity->location,    
            'bg_color' => $bgColor,
            'vibe_name' => $activity->vibe->name ?? '',
            'vibe_icon' => $activity->vibe->icon ?? '',
            'user_name' => $userData['name'] ?? '',
            'user_profile_image' => $userData['profile_image'] ?? '',
            'user_time' => $userData['time'] ?? '',
        ];
    });

    return response()->json([
        'message' => 'Matching activities found successfully',
        'status' => 200,
        'data' => $activitiesWithUserDetails,
    ]);
}




    public function friendcount(Request $request)
{
    $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // Fetch matching activities where status is 2
    $matchingActivities = Activity::where('user_id', $user->id)
                                    ->where('status', 2)
                                    ->get();

    // Get activity IDs related to the matching activities
    $activityIds = $matchingActivities->pluck('id'); 

    // Fetch interests for the given activity IDs
    $interestIds = OtherInterest::whereIn('activity_id', $activityIds)->get();

    // Fetch the users associated with these interests
    $userDetailsFromInterest = $interestIds->pluck('user_id');

    $likeUser = SlideLike::where('matched_user', $user->id);
    $likeUserDetails = $likeUser->pluck('matching_user'); 
    return $likeUserDetails;

    // Fetch the user details based on the user IDs
    $userDetailsFromInterest2 = User::whereIn('id', $userDetailsFromInterest)->get();
    $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

    $userList = $userDetailsFromInterest2->map(function ($user) {
        $imagePath = null;

        if ($user->profile_image) {
            $images = json_decode($user->profile_image, true); 
            if (is_array($images) && count($images)) {
                $imagePath = reset($images);
            }
        }

        $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$user->id)->orderBy('id','DESC')->first();

        return [
            'id' => $user->id,
            'user_rendom' => $user->rendom,
            'name' => $user->name,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'match',
            'last_message' => $chat->message ?? null,
        ];
    });

    // Prepare the list of users who liked the current user
    $likeUserList = $likeUserDetails2->map(function ($user) {
        $imagePath = null;

        if ($user->profile_image) {
            $images = json_decode($user->profile_image, true); 
            if (is_array($images) && count($images)) {
                $imagePath = reset($images);
            }
        }
        $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$user->id)->orderBy('id','DESC')->first();

        return [
            'id' => $user->id,
            'user_rendom' => $user->rendom,
            'name' => $user->name,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
        ];
    });

    $CupidMatches = Cupid::where('user_id_1', $user->id)
                        ->orWhere('user_id_2', $user->id)
                        ->get()->unique();

// return $CupidMatches;
    // Prepare the list of matched users from Cupid matches
    $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
        $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
        $matchedUser = User::find($matchedUserId);

        if (!$matchedUser) return null;

        $images = json_decode($matchedUser->profile_image, true);
        $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;
        $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$matchedUser->id)
        ->orderBy('id','DESC')->first();
        return [
            'id' => $matchedUser->id,
            'user_rendom' => $matchedUser->rendom,
            'name' => $matchedUser->name,
            'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
            'form' => 'match',
            'last_message' => $chat->message ?? null,
        ];
    })->filter();  // Remove null values

    // Ensure that all users are Eloquent collections
    $userList = collect($userList);   // Ensure it's a collection
    $likeUserList = collect($likeUserList);   // Ensure it's a collection
    $matchedUsers = collect($matchedUsers);   // Ensure it's a collection

    // Merge all users into one collection (activity users, liked users, and cupid users)
    $matchUsers = $userList->merge($likeUserList)->merge($matchedUsers);

    return response()->json([
        'message' => 'Friend and Cupid data fetched successfully',
        'status' => 200,
        'data' => [
            'match_users' => $matchUsers->unique('rendom'), 
            'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
            'like_count' => $interestIds->count(),
        ]
    ]);
}


    // public function friendcount(Request $request)
    // {
    //     $user = Auth::user(); 

    //     if (!$user) {
    //         return response()->json(['message' => 'User not authenticated'], 401);
    //     }

    //     $matchingActivities = Activity::where('user_id', $user->id)
    //                                     ->where('status', 2)
    //                                     ->get();

    //     $activityIds = $matchingActivities->pluck('id'); 

    //     $interestIds = OtherInterest::whereIn('activity_id', $activityIds)->get();

    //     $userDetailsFromInterest = $interestIds->pluck('user_id');

    //     $likeUser = SlideLike::where('matching_user', $user->id);
    //     $likeUserDetails = $likeUser->pluck('matching_user'); 

    //     $userDetailsFromInterest2 = User::whereIn('id', $userDetailsFromInterest)->get();
    //     $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

    //     $userList = $userDetailsFromInterest2->map(function ($user) {
    //         $imagePath = null;

    //         if ($user->profile_image) {
    //             $images = json_decode($user->profile_image, true); 
    //             if (is_array($images) && count($images)) {
    //                 $imagePath = reset($images);
    //             }
    //         }

    //         return [
    //             'id' => $user->id,
    //             'rendom' => $user->rendom,
    //             'name' => $user->name,
    //             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
    //             'form' => 'match',
    //         ];
    //     });

    //     // Prepare the list of users who liked the current user
    //     $likeUserList = $likeUserDetails2->map(function ($user) {
    //         $imagePath = null;

    //         if ($user->profile_image) {
    //             $images = json_decode($user->profile_image, true); 
    //             if (is_array($images) && count($images)) {
    //                 $imagePath = reset($images);
    //             }
    //         }

    //         return [
    //             'id' => $user->id,
    //             'rendom' => $user->rendom,
    //             'name' => $user->name,
    //             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
    //             'form' => 'activity',
    //         ];
    //     });

    //     // Fetch Cupid matches
    //     $CupidMatches = Cupid::where('user_id_1', $user->id)
    //                         ->orWhere('user_id_2', $user->id)
    //                         ->get();

    //     // Prepare the list of matched users from Cupid matches
    //     $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
    //         $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
    //         $matchedUser = User::find($matchedUserId);

    //         if (!$matchedUser) return null;

    //         $images = json_decode($matchedUser->profile_image, true);
    //         $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;

    //         return [
    //             'id' => $matchedUser->id,
    //             'rendom' => $matchedUser->rendom,
    //             'name' => $matchedUser->name,
    //             'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
    //             'form' => 'match',
    //         ];
    //     })->filter();  // Remove null values

    //     // Merge all users into one collection (activity users, liked users, and cupid users)
    //     $matchUsers = $userList->merge($likeUserList)->merge($matchedUsers);

    //     return response()->json([
    //         'message' => 'Friend and Cupid data fetched successfully',
    //         'status' => 200,
    //         'data' => [
    //             'match_users' => $matchUsers,  // Combined list of all users
    //             'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
    //             'like_count' => $interestIds->count(),
    //         ]
    //     ]);
    // }


    
    

    // public function filteractivity(Request $request)
    // {
    //     $location = $request->input('location');
    //     $when_time = $request->input('when_time');
    //     $start_time = $request->input('start_time');  // Assuming this is a time like "4:31 PM"
    //     $end_time = $request->input('end_time');      // Assuming this is a time like "6:31 PM"
    //     $expense_id = $request->input('expense_id');  // This should now be an array, e.g., ["1"]
    //     $interests_id = $request->input('interests_id');
    //     $other_activity = $request->input('other_activity');
    //     $query = Activity::query();
    //     $filterApplied = false;
    
    //     // Filter based on location
    //     if ($location) {
    //         $query->where('location', 'like', '%' . $location . '%');
    //         $filterApplied = true;
    //     }

    //     if ($when_time) {
    //         $query->where('when_time', $when_time);
    //         $filterApplied = true;
    //     }

    //     if ($start_time && $end_time) {

    //         $startTimeFormatted = \Carbon\Carbon::parse($start_time)->format('H:i:s');
    //         $endTimeFormatted = \Carbon\Carbon::parse($end_time)->format('H:i:s');
    
    //         $query->where(function($q) use ($startTimeFormatted, $endTimeFormatted) {
    //             $q->WhereBetween('end_time', [$startTimeFormatted, $endTimeFormatted])
    //               ->orWhere(function($query) use ($startTimeFormatted, $endTimeFormatted) {
    //                   $query->where('start_time', '<=', $startTimeFormatted)
    //                         ->where('end_time', '>=', $endTimeFormatted);
    //               });
    //         });
    
    //         $filterApplied = true;
    //     }

    //     if ($expense_id && is_array($expense_id)) {
    //         $query->whereIn('expense_id', $expense_id);
    //         $filterApplied = true;
    //     }

    //     if ($interests_id && is_array($interests_id)) {
    //         $query->whereIn('interests_id', $interests_id);
    //         $filterApplied = true;
    //     }

    //     if (!$filterApplied) {
    //         return response()->json([
    //             'message' => 'No filters applied, returning all activities',
    //             'status' =>  200,
    //             'data' =>  [],
    //         ], 200);
    //     }

    //     $activities = $query->with('user')->get();  
    //     $activities->makeHidden(['created_at', 'updated_at', 'deleted_at', 'id', 'user_id']);

    //     $responseData = $activities->map(function($activity) {
            
    //         $hash = md5($activity->id);
    //         $r = hexdec(substr($hash, 0, 2));
    //         $g = hexdec(substr($hash, 2, 2));
    //         $b = hexdec(substr($hash, 4, 2));
            
    //         $lightenFactor = 0.5; 
    //         $r = round($r + (255 - $r) * $lightenFactor);
    //         $g = round($g + (255 - $g) * $lightenFactor);
    //         $b = round($b + (255 - $b) * $lightenFactor);
  
    //         $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);
    
    //         $userDetails = $activity->user;

    //         $profileImages = json_decode($userDetails->profile_image, true);
    //         $profileImageUrl = isset($profileImages[1]) ? url('uploads/app/profile_images/' . $profileImages[1]) : null;
 
    //         return [
    //             'title' => $activity->title,
    //             'rendom' => $activity->rendom,
    //             'location' => $activity->location,
    //             'bg_color' => $bgColor,
    //             'vibe_name' => $activity->vibe->name ?? '',
    //             'vibe_icon' => $activity->vibe->icon ?? '',
    //             'user_name' => $userDetails->name,
    //             'user_profile_image' => $profileImageUrl,  
    //             'user_time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'),
    //         ];
    //     });
    
    //     return response()->json([
    //         'message' => 'Activities retrieved successfully',
    //         'status' => 200,
    //         'data' => $responseData,
    //     ], 200);
    // }
    
    
    public function filteractivity(Request $request)
    {
        $location = $request->input('location');
        $when_time = $request->input('when_time');
        $end_time = $request->input('end_time');
        $expense_id = $request->input('expense_id');  // Array e.g. ["1", "3"]
        $interests_id = $request->input('interests_id');  // Array e.g. ["2", "4"]

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

        if ($end_time) {
            // Clean weird unicode spaces like ' ' (narrow no-break space)
            $cleanedEndTime = preg_replace('/[^\x20-\x7E]/', '', $end_time);
        
            // Convert to proper H:i:s
            $endTimeFormatted = \Carbon\Carbon::parse($cleanedEndTime)->format('H:i:s');
        
            $query->whereTime('end_time', '>=', $endTimeFormatted);
            $filterApplied = true;
        }
        

        // 🔹 Filter by expense_id (JSON string, use LIKE or FIND_IN_SET)
        if ($expense_id && is_array($expense_id)) {
            $query->where(function ($q) use ($expense_id) {
                foreach ($expense_id as $id) {
                    $q->orWhere('expense_id', 'like', '%"'.$id.'"%');
                }
            });
            $filterApplied = true;
        }

        // 🔹 Filter by interests_id (JSON string, use LIKE or FIND_IN_SET)
        if ($interests_id && is_array($interests_id)) {
            $query->where(function ($q) use ($interests_id) {
                foreach ($interests_id as $id) {
                    $q->orWhere('interests_id', 'like', '%"'.$id.'"%');
                }
            });
            $filterApplied = true;
        }

        if (!$filterApplied) {
            return response()->json([
                'message' => 'No filters applied, returning all activities',
                'status' => 200,
                'data' => [],
            ], 200);
        }

        // 🔹 Get filtered activities
        $activities = $query->with('user', 'vibe')->get();  
        $activities->makeHidden(['created_at', 'updated_at', 'deleted_at', 'id', 'user_id']);

        $responseData = $activities->map(function($activity) {
            $hash = md5($activity->id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $lightenFactor = 0.5; 
            $r = round($r + (255 - $r) * $lightenFactor);
            $g = round($g + (255 - $g) * $lightenFactor);
            $b = round($b + (255 - $b) * $lightenFactor);
            $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

            $userDetails = $activity->user;
            $profileImages = json_decode($userDetails->profile_image, true);
            $profileImageUrl = isset($profileImages[1]) ? url('uploads/app/profile_images/' . $profileImages[1]) : null;

            return [
                'title' => $activity->title,
                'rendom' => $activity->rendom,
                'location' => $activity->location,
                'bg_color' => $bgColor,
                'vibe_name' => $activity->vibe->name ?? '',
                'vibe_icon' => $activity->vibe->icon ?? '',
                'user_name' => $userDetails->name,
                'user_profile_image' => $profileImageUrl,  
                'user_time' => \Carbon\Carbon::parse($userDetails->created_at)->format('d-F H:i'),
            ];
        });


        return response()->json([
            'message' => 'Activities retrieved successfully',
            'status' => 200,
            'data' => $responseData,
        ], 200);
    }


        
        public function vibeactivitycount()
        {

            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Unauthorized. Please log in.',
                ], 401);
            }

            $vibes = Vibes::all();
        
            $vibeWithActivityCount = [];
        
            foreach ($vibes as $vibe) {
                $activityCount = Activity::where('vibe_id', $vibe->id)->where('status',2)->count();
                $vibeWithActivityCount[] = [
                    'id' => $vibe->id,
                    'name' => $vibe->name,
                    'activity_id' => $vibe->activity_id,
                    // 'image' => $vibe->image,
                    'status' => $vibe->status,
                    'icon' => $vibe->icon,
                    'activity_count' => $activityCount
                ];
            }
            return response()->json([
                'message' => 'Vibe activity counts fetched successfully.',
                'status' => 200,
                'data' => $vibeWithActivityCount
            ]);
        }







public function vibeactivitydetails(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    $validator = Validator::make($request->all(), [
        'vibe_id' => 'required',
    ]);

    $user_id = Auth::id();
    $user = User::where('id', $user_id)->first();

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
            'status' => 201,
        ], 422);
    }

    if ($request->vibe_id == 0) {
        $vibes = Vibes::all();

        $vibeWithActivityCount = [];

        foreach ($vibes as $vibe) {
            $hash = md5($vibe->id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));

            $lightenFactor = 0.5; 
            $r = round($r + (255 - $r) * $lightenFactor);
            $g = round($g + (255 - $g) * $lightenFactor);
            $b = round($b + (255 - $b) * $lightenFactor);

            $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

            // $activityCount = Activity::where('vibe_id', $vibe->id)->where('status', 2)->count();

            $currentTime = Carbon::now('Asia/Kolkata'); 
            $todayDate = Carbon::today('Asia/Kolkata');
            $activities = Activity::orderBy('id', 'DESC')
                ->where('vibe_id', $vibe->id)
                ->where('status', 2)
                ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
                ->get();
    
            $filteredActivities = [];
    
            foreach ($activities as $activity) {
                $whenTime = Carbon::parse($activity->when_time)->setTimezone('Asia/Kolkata');
    
                try {
                    $endTime = Carbon::createFromFormat('h:i A', $activity->end_time)->setTimezone('Asia/Kolkata');
                } catch (\Exception $e) {
                    Log::error('Error parsing end_time:', ['error' => $e->getMessage(), 'activity' => $activity->toArray()]);
                    continue;
                }

                $combinedDateTime = $whenTime->copy()->setTimeFromTimeString($endTime->toTimeString());
    
                if ($combinedDateTime >= $currentTime) {
                    $filteredActivities[] = $activity;
                }
            }
            
            $filteredActivities = collect($filteredActivities);

            $vibeWithActivityCount[] = [
                'id' => $vibe->id,
                'name' => $vibe->name,
                'status' => $vibe->status,
                'icon' => $vibe->icon,
                'bg_color' => $bgColor, 
                'activity_count' => $filteredActivities->count(),
            ];
        }

        return response()->json([
            'message' => 'Vibe activity counts fetched successfully.',
            'status' => 200,
            'data' => $vibeWithActivityCount
        ]);
    } else {
        $vibe = Vibes::find($request->vibe_id);

        if (!$vibe) {
            return response()->json([
                'message' => 'Vibe not found.',
                'status' => 201,
                'data' => [],
            ], 404);
        }

        $currentTime = Carbon::now('Asia/Kolkata'); 
        $todayDate = Carbon::today('Asia/Kolkata');

        $activities = Activity::orderBy('id', 'DESC')
            ->where('vibe_id', $vibe->id)
            ->where('status', 2)
            ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
            ->get();

        $filteredActivities = [];

        foreach ($activities as $activity) {
            $whenTime = Carbon::parse($activity->when_time)->setTimezone('Asia/Kolkata');

            try {
                $endTime = Carbon::createFromFormat('h:i A', $activity->end_time)->setTimezone('Asia/Kolkata');
            } catch (\Exception $e) {
                Log::error('Error parsing end_time:', ['error' => $e->getMessage(), 'activity' => $activity->toArray()]);
                continue;
            }

            $combinedDateTime = $whenTime->copy()->setTimeFromTimeString($endTime->toTimeString());

            if ($combinedDateTime >= $currentTime) {
                $filteredActivities[] = $activity;
            }
        }

        $filteredActivities = collect($filteredActivities);

        $vibeDetails = [
            'id' => $vibe->id,
            'name' => $vibe->name,
            'status' => $vibe->status,
            'icon' => $vibe->icon,
            'activities' => $filteredActivities->map(function ($activity) {

                $hash = md5($activity->id);
                $r = hexdec(substr($hash, 0, 2));
                $g = hexdec(substr($hash, 2, 2));
                $b = hexdec(substr($hash, 4, 2));

                $lightenFactor = 0.5;
                $r = round($r + (255 - $r) * $lightenFactor);
                $g = round($g + (255 - $g) * $lightenFactor);
                $b = round($b + (255 - $b) * $lightenFactor);

                $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

                $user_rendom = User::where('id', $activity->user_id)->first();

                $profileImageUrl = null;
                if ($user_rendom->profile_image) {
                    $profileImages = json_decode($user_rendom->profile_image, true);

                    if (!empty($profileImages) && isset($profileImages[1])) {
                        $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
                    }
                }

                return [
                    'rendom' => $activity->rendom,
                    'when_time' => $activity->when_time,
                    'end_time' => $activity->end_time,
                    'title' => $activity->title,
                    'location' => $activity->location,
                    'bg_color' => $bgColor,
                    'how_many' => $activity->how_many,
                    'vibe_name' => $activity->vibe->name ?? '',
                    'vibe_icon' => $activity->vibe->icon ?? '',
                    'user_name' => $user_rendom->name,
                    'user_profile_image' => $profileImageUrl,
                    'user_time' => \Carbon\Carbon::parse($activity->created_at)->format('d-F H:i'),
                    'status' => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
                ];
            })
        ];

        return response()->json([
            'message' => 'Vibe activity details fetched successfully.',
            'status' => 200,
            'data' => $vibeDetails
        ]);
    }
}


    //     public function vibeactivitydetails(Request $request)
    //     {
            
    //         if (!Auth::check()) {
    //             return response()->json([
    //                 'message' => 'Unauthorized. Please log in.',
    //             ], 401);
    //         }


    //         $validator = Validator::make($request->all(), [
    //             'vibe_id' => 'required',
    //         ]);
            
    //         $user_id = Auth::id();
    //         $user = User::where('id',$user_id)->first();

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'message' => 'Validation failed',
    //                 'errors' => $validator->errors(),
    //                 'status' => 201,
    //             ], 422);
    //         }

    //         if ($request->vibe_id  == 0) {

    //             $vibes = Vibes::all();

    //             $vibeWithActivityCount = [];
    
    //             foreach ($vibes as $vibe) {
    //                 // Generate a unique color for each vibe
    //                 $hash = md5($vibe->id);
    //                 $r = hexdec(substr($hash, 0, 2));
    //                 $g = hexdec(substr($hash, 2, 2));
    //                 $b = hexdec(substr($hash, 4, 2));
                    
    //                 $lightenFactor = 0.5;  // Adjust the lightening factor to 50%
    //                 $r = round($r + (255 - $r) * $lightenFactor);
    //                 $g = round($g + (255 - $g) * $lightenFactor);
    //                 $b = round($b + (255 - $b) * $lightenFactor);
                    
    //                 // Convert back to hex format
    //                 $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);
    
    //                 // Count activities associated with each vibe
    //                 $activityCount = Activity::where('vibe_id', $vibe->id)->where('status',2)->count();
                    
    //                 // Add vibe with activity count and background color
    //                 $vibeWithActivityCount[] = [
    //                     'id' => $vibe->id,
    //                     'name' => $vibe->name,
    //                     // 'activity_id' => $vibe->activity_id,
    //                     'status' => $vibe->status,
    //                     'icon' => $vibe->icon,
    //                     'bg_color' => $bgColor, // Background color based on vibe ID
    //                     'activity_count' => $activityCount
    //                 ];
    //             }
    
    //             return response()->json([
    //                 'message' => 'Vibe activity counts fetched successfully.',
    //                 'status' => 200,
    //                 'data' => $vibeWithActivityCount
    //             ]);
    //         }else{

    //             $vibe = Vibes::find($request->vibe_id);

    //             if (!$vibe) {
    //                 return response()->json([
    //                     'message' => 'Vibe not found.',
    //                     'status' => 201,
    //                     'data' => [],
    //                 ], 404);
    //             }
    //             $currentTime = Carbon::now('Asia/Kolkata');  // Current time in Asia/Kolkata
    //             $todayDate = Carbon::today('Asia/Kolkata'); 

    //             // $activities = Activity::where('vibe_id', $vibe->id)->where('status',2)->get();
    //             $activities = Activity::orderBy('id', 'DESC')
    //             ->where('vibe_id', $vibe->id)
    //                 ->where('status', 2) 
    //                 ->whereDate('when_time', '>=', $todayDate->format('Y-m-d')) 
    //                 ->get();
    
    // $filteredActivities = [];
    
    // foreach ($activities as $activity) {

    //     $whenTime = Carbon::parse($activity->when_time)->setTimezone('Asia/Kolkata');

    //     $endTime = Carbon::createFromFormat('h:i A', $activity->end_time)->setTimezone('Asia/Kolkata'); 

    //     $combinedDateTime = $whenTime->copy()->setTimeFromTimeString($endTime->toTimeString());

    //     if ($combinedDateTime >= $currentTime) {

    //         $filteredActivities[] = $activity;
    //     }
    // }

    //             $vibeDetails = [
    //                 'id' => $vibe->id,
    //                 'name' => $vibe->name,
    //                 // 'activity_id' => $vibe->activity_id,
    //                 'status' => $vibe->status,
    //                 'icon' => $vibe->icon,
    //                 'activities' => $filteredActivities->map(function ($activity) {

    //                 $hash = md5($activity->id);
    //                 $r = hexdec(substr($hash, 0, 2));
    //                 $g = hexdec(substr($hash, 2, 2));
    //                 $b = hexdec(substr($hash, 4, 2));
            
    //                 $lightenFactor = 0.5;
    //                 $r = round($r + (255 - $r) * $lightenFactor);
    //                 $g = round($g + (255 - $g) * $lightenFactor);
    //                 $b = round($b + (255 - $b) * $lightenFactor);
            
    //                 $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

    //                     $user_rendom = User::where('id', $activity->user_id)->first();

    //                     $profileImageUrl = null;
    //                     if ($user_rendom->profile_image) {
    //                         $profileImages = json_decode($user_rendom->profile_image, true);
                    
    //                         if (!empty($profileImages) && isset($profileImages[1])) {
    //                             $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
    //                         }
    //                     }

    //                     return [
    //                         'rendom' => $activity->rendom,
    //                         'when_time' => $activity->when_time,
    //                         'end_time' => $activity->end_time,
    //                         'title' => $activity->title,
    //                         'location' => $activity->location,
    //                         'bg_color' => $bgColor,
    //                         'how_many' => $activity->how_many,
    //                         'vibe_name' => $activity->vibe->name ?? '',
    //                         'vibe_icon' => $activity->vibe->icon ?? '',
    //                         'user_name' => $user_rendom->name,
    //                         'user_profile_image' => $profileImageUrl,
    //                         'user_time' => \Carbon\Carbon::parse($activity->created_at)->format('d-F H:i'),
    //                         'status' => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
    //                     ];
    //                 })
    //             ];

    //             return response()->json([
    //                 'message' => 'Vibe activity details fetched successfully.',
    //                 'status' => 200,
    //                 'data' => $vibeDetails
    //             ]);
          
    //     }
    //     }



public function updateConfirm(Request $request)
{
    $request->validate([
        'random' => 'required|string', 
        'pactup' => 'required', 
        'activity_rendom' => 'required', 
    ]);

    $random = $request->input('random');
    $pactup = $request->input('pactup');
    $activity_rendom = $request->input('activity_rendom');



    $user = User::where('rendom', $random)->first();
    $activity_rendom_1 = Activity::where('rendom', $activity_rendom)->first();

    if (!$activity_rendom_1) {
        return response()->json([
        'message' => 'Activity not found',
        'data'=>[],
        'status'=>201
    ], 200);
    }

    if (!$user) {
        return response()->json([
            'message' => 'User not found.',
            'status' => 201,
            'data' => [],
    ], 201);
    }

    
    // return $user->id;

    $otherInterest = OtherInterest::where('user_id', $user->id)->where('activity_id',$activity_rendom_1->id)->first();

    if ($otherInterest) {
        $otherInterest->update(['confirm' => 0]);

        return response()->json([
            'message' => 'Confirm updated successfully to',
            'status' => 200,
            'data' => [],
        ], 200);
    }

    return response()->json([
        'message' => 'No matching record in OtherInterest table.',
        'status' => 201,
        'data' => [],
], 201);
}


        

}

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
use App\Models\AdminCity;
use App\Models\Chat;
use App\Models\CoinCategory;
use App\Models\Cupid;
use App\Models\LikeActivity;
use App\Models\SlideLike;
use Illuminate\Support\Facades\Http;
use App\Models\OtherInterest;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class ActivityController extends Controller
{


  public function verifyCity(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->latitude || !$user->longitude) {
            return response()->json([
                'message' => 'User location not available.',
            ], 400);
        }

        $lat = $user->latitude;
        $lon = $user->longitude;

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$lat,$lon",
            'key' => $apiKey,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed to contact Google Maps API.',
            ], 500);
        }

        $data = $response->json();

        if (empty($data['results'])) {
            return response()->json([
                'message' => 'No results from Google Maps.',
            ], 404);
        }

        $city = null;
        foreach ($data['results'][0]['address_components'] as $component) {
            if (in_array('locality', $component['types'])) {
                $city = $component['long_name'];
                break;
            }
        }

        if (!$city) {
            return response()->json([
                'message' => 'City not found from your location.',
            ], 404);
        }

        $matchedCity = AdminCity::where('city_name', 'LIKE', "%$city%")
            ->where('status', 1)
            ->first();

        if (!$matchedCity) {
            return response()->json([
                'message' => 'Your city is not active in our system.',
                'detected_city' => $city,
            ], 404);
        }

        return response()->json([
            'message' => 'City matched and active.',
            'city' => $matchedCity->city_name,
        ]);
    }
    
  private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function activitystore(Request $request)
    {
        // if (!Auth::check()) {
        //     return response()->json([
        //         'message' => 'Unauthorized. Please log in.',
        //     ], 401);
        // }
    
        // $user = Auth::user();

        // if ($user->subscription == 0) {

        //     $currentDate = now();
        //     $sevenDaysAgo = now()->subWeek();

        //     $activityCount = Activity::where('user_id', $user->id)
        //         ->whereBetween('created_at', [$sevenDaysAgo, $currentDate])
        //         ->count();
        //         $activitysubscriptioncount =  ActivitySubscription::orderBy('id','DESC')->first();
  
        //     if ($activityCount >= $activitysubscriptioncount->activity_count) {
        //         return response()->json([
        //             'message' => 'You can only create up to activities per week due to your subscription.',
        //             'status' => 201,
        //         ], 201);
        //     }
    
        //     $validator = Validator::make($request->all(), [
        //         'title' => 'nullable',
        //         'location' => 'nullable',
        //         'rendom' => 'nullable',
        //         'how_many' => 'nullable|integer',   
        //         'start_time' => 'nullable',
        //         'end_time' => 'nullable',
        //         'when_time' => 'nullable',
        //         // 'interests_id' => 'nullable',
        //         // 'interests_id.*' => 'integer',
        //         'vibe_id' => 'nullable',
        //         'expense_id' => 'nullable',  
        //         'description' => 'nullable',
        //         'other_activity' => 'nullable|string',
        //         'image' => 'nullable',
        //         'amount' => 'nullable|numeric',
        //         'activity_id' => 'nullable|exists:activity_temp_table,id', 
        //         'friend_rendom' => 'nullable', 
        //         'update_status' => 'nullable|in:update,final', 
        //     ]);
        // } else {
        //     // When subscription is not 0, there is no limit to activities or interests.
        //     $validator = Validator::make($request->all(), [
        //         'title' => 'nullable',
        //         'rendom' => 'nullable',
        //         'location' => 'nullable',
        //         'how_many' => 'nullable|integer',
        //         'start_time' => 'nullable',
        //         'end_time' => 'nullable',
        //         'when_time' => 'nullable',
        //         // 'interests_id' => 'nullable',
        //         'vibe_id' => 'nullable',
        //         'expense_id' => 'nullable',  
        //         'description' => 'nullable',
        //         'other_activity' => 'nullable|string',
        //         'image' => 'nullable',
        //         'amount' => 'nullable|numeric',
        //         'friend_rendom' => 'nullable',
        //         'activity_id' => 'nullable|exists:activity_temp_table,id', 
        //         'update_status' => 'nullable|in:update,final', 
        //     ]);
        // }
    
        // // Handle validation errors
        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors(),
        //         'status' => 201,
        //     ], 422);
        // }
    
         if (!Auth::check()) {
                return response()->json([
                    'message' => 'Unauthorized. Please log in.',
                ], 401);
            }

            $user = Auth::user();
            if ($user->admin_city == null) {
                return response()->json([
                    'message' => 'City Not Selected',
                    'status' => 202,
                ], 202);
            }
            $now = Carbon::now('Asia/Kolkata');

            // Check active subscription for 'Activitys' type
            $activeSubscription = UserSubscription::where('user_id', $user->id)
                ->where('type', 'Activitys')
                ->where('is_active', 1)
                ->where('activated_at', '<=', $now)
                ->where('expires_at', '>=', $now)
                ->first();

            if ($activeSubscription) {
                // Subscribed user — get monthly_activities_coin limit from plan
                $coinCategory = CoinCategory::find($activeSubscription->plan_id);
                $allowedCount = $coinCategory ? (int)$coinCategory->monthly_activities_coin : 0;

                $startDate = Carbon::parse($user->created_at)->startOfDay();
                $nowStartOfDay = $now->copy()->startOfDay();

                $currentIntervalStart = $startDate;
                $exceeded = false;

                while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
                    $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();

                    // Count activities created in current 30-day interval
                    $activityCount = Activity::where('user_id', $user->id)
                        ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
                        ->count();

                    if ($activityCount >= $allowedCount) {
                        $exceeded = true;
                        break;
                    }

                    $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
                }

                if ($exceeded) {
                    return response()->json([
                        'message' => 'You have used all your activity coins for this month. Please renew or upgrade your plan.',
                        'data' => [
                            'interval_start' => $currentIntervalStart->toDateString(),
                            'interval_end' => $currentIntervalEnd->toDateString(),
                            'activities_created' => $activityCount,
                            'allowed_activities' => $allowedCount,
                        ],
                        'status' => 203,
                    ]);
                }
            } else {
                    // Free user — weekly activity_count limit check with timezone aware
                    $currentDate = $now;  // use same $now with Asia/Kolkata timezone
                    $sevenDaysAgo = $now->copy()->subWeek();

                    $activityCount = Activity::where('user_id', $user->id)
                        ->whereBetween('created_at', [$sevenDaysAgo, $currentDate])
                        ->count();

                    $activitysubscriptioncount = ActivitySubscription::orderBy('id', 'DESC')->first();

                    if ($activityCount >= $activitysubscriptioncount->activity_count) {
                        return response()->json([
                            'message' => 'You can only create up to ' . $activitysubscriptioncount->activity_count . ' activities per week due to your subscription.',
                            'status' => 203,
                        ], 201);
                    }
                }

            // Now continue with your existing validation & processing logic without any other changes

            // Validation rules remain the same for both subscription and free users:
            $validator = Validator::make($request->all(), [
                'title' => 'nullable',
                'location' => 'nullable',
                'rendom' => 'nullable',
                'how_many' => 'nullable|integer',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'when_time' => 'nullable',
                'vibe_id' => 'nullable',
                'expense_id' => 'nullable',
                'description' => 'nullable',
                'other_activity' => 'nullable|string',
                'image' => 'nullable',
                'amount' => 'nullable|numeric',
                'activity_id' => 'nullable|exists:activity_temp_table,id',
                'friend_rendom' => 'nullable',
                'update_status' => 'nullable|in:update,final',
            ]);

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
            $activityTemp->friend_rendom = $request->has('friend_rendom')
            ? (
                is_string($request->friend_rendom) && $this->isJson($request->friend_rendom)
                    ? (is_array(json_decode($request->friend_rendom, true))
                        ? implode(',', json_decode($request->friend_rendom, true))
                        : $request->friend_rendom)
                    : $request->friend_rendom
            )
            : $activityTemp->friend_rendom;

            $activityTemp->friend_number = $request->has('friend_number')
            ? (
                is_string($request->friend_number) && $this->isJson($request->friend_number)
                    ? (is_array(json_decode($request->friend_number, true))
                        ? implode(',', json_decode($request->friend_number, true))
                        : $request->friend_number)
                    : $request->friend_number
            )
            : $activityTemp->friend_number;

            $activityTemp->interests_id = isset($user->interest) ? implode(',', (array)$user->interest) : $user->interest;
            // $activityTemp->vibe_id = $request->vibe_id ?? $activityTemp->vibe_id;
            $activityTemp->vibe_id = isset($request->vibe_id) ? implode(',', (array)$request->vibe_id) : $activityTemp->vibe_id;
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
                'friend_rendom' => $activityTemp->friend_rendom,
                'friend_number' => $activityTemp->friend_number,
                'status' => $activityTemp->status,
                'title' => $activityTemp->title,
                'description' => $activityTemp->description,
                'location' => $activityTemp->location,
                'other_activity' => $activityTemp->other_activity,
                'image' => $activityTemp->image,
                'amount' => $activityTemp->amount,
                'rendom' => $activityTemp->rendom,
                'update_count' => 0,
            ]); 

            if (!empty($activityTemp->friend_number)) {
                $phoneNumbers = array_filter(array_map('trim', explode(',', $activityTemp->friend_number)));

                if (!empty($phoneNumbers)) {
                    $matchingUsers = User::whereIn('number', $phoneNumbers)->get();

                    foreach ($matchingUsers as $matchedUser) {
                        $exists = OtherInterest::where('user_id', $user->id)
                            ->where('user_id_1', $matchedUser->id)
                            ->where('activity_id', $activity->id)
                            ->exists();

                        if (!$exists) {
                            OtherInterest::create([
                                'user_id_1'     => $user->id,
                                'activity_id' => $activity->id,
                                'user_id'   => $matchedUser->id,
                                'confirm'     => 6,
                            ]);
                        }
                    }
                }
            }

            if (!empty($activityTemp->friend_rendom)) {
                $phonerendom = array_filter(array_map('trim', explode(',', $activityTemp->friend_rendom)));

                if (!empty($phonerendom)) {
                    $matchingUsers = User::whereIn('rendom', $phonerendom)->get();

                    foreach ($matchingUsers as $matchedUser) {
                        $exists = OtherInterest::where('user_id', $user->id)
                            ->where('user_id_1', $matchedUser->id)
                            ->where('activity_id', $activity->id)
                            ->where('confirm', 6)
                            ->exists();

                        if (!$exists) {
                            OtherInterest::create([
                                'user_id_1'     => $user->id,
                                'activity_id' => $activity->id,
                                'user_id'   => $matchedUser->id,
                                'confirm'     => 6,
                            ]);
                        }
                    }
                }
            }


    
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
            'friend_rendom' => is_string($request->friend_rendom) && $this->isJson($request->friend_rendom)
    ? implode(',', json_decode($request->friend_rendom, true))
    : $request->friend_rendom,
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


   public function activityedit(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $user = Auth::user();

        $activity = Activity::where('user_id', $user->id)
                            ->where('rendom', $request->activity_rendom)
                            ->first();

        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found or unauthorized.',
            ], 404);
        }

        if (!is_null($activity) && $activity->update_count == 0) {
        $activity->where_to = $request->where_to ?? $activity->where_to;
        $activity->when_time = $request->when_time ?? $activity->when_time;
        $activity->how_many = $request->how_many ?? $activity->how_many;
        $activity->start_time = $request->start_time ?? $activity->start_time;
        $activity->end_time = $request->end_time ?? $activity->end_time;
        $activity->friend_rendom = $request->friend_rendom ?? $activity->friend_rendom;

        $activity->interests_id = isset($user->interest) ? implode(',', (array)$user->interest) : $activity->interests_id;
        $activity->vibe_id = isset($request->vibe_id) ? implode(',', (array)$request->vibe_id) : $activity->vibe_id;
        $activity->expense_id = isset($request->expense_id) ? implode(',', (array)$request->expense_id) : $activity->expense_id;

        $activity->other_activity = $request->other_activity ?? $activity->other_activity;
        $activity->status = 2;
        $activity->title = $request->title ?? $activity->title;
        $activity->description = $request->description ?? $activity->description;
        $activity->location = $request->location ?? $activity->location;
        $activity->amount = $request->amount ?? $activity->amount;
        $activity->update_count = $request->update_count ?? '';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('activity_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $activity->image = 'activity_images/' . $imageName;
        }


        $activity->save();
        }else{
            return response()->json([
                    'message' => 'You have already updated your activity once.',
                    // 'data' => $activity,
                    'status' => 208,
                ]);
        }

        return response()->json([
            'message' => 'Activity updated successfully.',
            'data' => $activity,
            'status' => 200,
        ]);
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
    $currentDateTime = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');

    $activities = Activity::orderBy('id', 'DESC')
        ->where('user_id', $user->id)
        ->where('status', 2)
        ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
        ->where(function ($query) use ($currentDateTime) {
            $query->whereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
            ", [$currentDateTime]);
        })
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

         
          $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                        $expenseIds = json_decode($activity->expense_id, true);
                    $firstExpenseName = null;
                    if (is_array($expenseIds) && count($expenseIds) > 0) {
                        $firstExpense = Expense::find($expenseIds[0]);
                        $firstExpenseName = $firstExpense->name ?? null;
                    }

        $activitiesData[] = [
            'rendom' => $activity->rendom,
            'when_time' => $activity->when_time,
            'end_time' => $activity->end_time,
            'title' => $activity->title,
            'location' => $activity->location,
            'bg_color' => $bgColor,
            'how_many' => $activity->how_many,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            'is_like' => true,
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            'user_name' => $user->name,
            'expense_name' => $firstExpenseName ?? '',
           'user_profile_image' => $profileImageUrl ?? '',
            'activity_image' => asset($activity->image),
           'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

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



public function useroldactivitys(Request $request)
{
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $currentTime = Carbon::now('Asia/Kolkata');  // Current time in Asia/Kolkata

    $activities = Activity::orderBy('id', 'DESC')
    ->where('user_id', $user->id)
    ->where('status', 2)
    ->where(function ($query) use ($currentTime) {
        $query->whereDate('when_time', '<', substr($currentTime, 0, 10)) // Past date
            ->orWhereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') < ?
            ", [$currentTime]);
    })
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



    if ($activities->isEmpty()) {
        return response()->json([
            'message' => 'No activities found',
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

         
          $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                        $expenseIds = json_decode($activity->expense_id, true);
                    $firstExpenseName = null;
                    if (is_array($expenseIds) && count($expenseIds) > 0) {
                        $firstExpense = Expense::find($expenseIds[0]);
                        $firstExpenseName = $firstExpense->name ?? null;
                    }

        $activitiesData[] = [
            'rendom' => $activity->rendom,
            'when_time' => $activity->when_time,
            'end_time' => $activity->end_time,
            'title' => $activity->title,
            'location' => $activity->location,
            'bg_color' => $bgColor,
            'how_many' => $activity->how_many,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            'expense_name' => $firstExpenseName ?? '',
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            'user_name' => $user->name,
           'user_profile_image' => $profileImageUrl ?? '',
            'activity_image' => asset($activity->image),
           'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

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



public function userinterestactivitys(Request $request)
{
    $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $currentTime = Carbon::now('Asia/Kolkata');

    //    $activityIds = OtherInterest::where('user_id', $user->id)
    //                     ->where('confirm', 0)
    //                     ->pluck('activity_id')
    //                     ->toArray();

            $activityIds = OtherInterest::where('user_id', $user->id)
                ->where(function($query) {
                    $query->where('confirm', 0)
                        ->orWhere('confirm', 4);
                })
                ->pluck('activity_id')
                ->toArray();

    if (empty($activityIds) || count($activityIds) == 0) {
        return response()->json([
            'message' => 'No matching activities found',
            'status'  => 200,
            'data'    => [],
        ]);
    }

    $activities = Activity::whereIn('id', $activityIds)
                    ->orderBy('id', 'DESC')
                    ->get(); 

        if ($activities->isEmpty()) {
            return response()->json([
                'message' => 'No activities found',
                'status' => 200,
                'data' => [],
            ]);
        }

        // ✅ Profile Image decode (2nd image)
        // $profileImageUrl = null;
        // if ($user->profile_image) {
        //     $profileImages = json_decode($user->profile_image, true);
        //     if (!empty($profileImages) && isset($profileImages[1])) {
        //         $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
        //     }
        // }

        $activitiesData = [];

        foreach ($activities as $activity) {

    $activityUser = User::find($activity->user_id);

            $profileImageUrl = null;
            if ($activityUser && $activityUser->profile_image) {
                $profileImages = json_decode($activityUser->profile_image, true);

                if (!empty($profileImages) && isset($profileImages[1])) {
                    $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
                }
            }
            
            // bg_color logic


            $hash = md5($activity->id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $lightenFactor = 0.5;
            $r = round($r + (255 - $r) * $lightenFactor);
            $g = round($g + (255 - $g) * $lightenFactor);
            $b = round($b + (255 - $b) * $lightenFactor);
            $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

            // Vibes
            $vibeNames = [];
            $vibeImages = [];
            $vibeIdsRaw = json_decode($activity->vibe_id, true);
            if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                $vibeIdList = explode(',', $vibeIdsRaw[0]);
                $vibes = Vibes::whereIn('id', $vibeIdList)->get();
                foreach ($vibes as $vibe) {
                    $vibeNames[] = $vibe->name;
                    $vibeImages[] = asset($vibe->icon);
                }
            }

            // Expense
            $expenseIds = json_decode($activity->expense_id, true);
            $firstExpenseName = null;
            if (is_array($expenseIds) && count($expenseIds) > 0) {
                $firstExpense = Expense::find($expenseIds[0]);
                $firstExpenseName = $firstExpense->name ?? null;
            }

            $authuser =  Auth::user();
                        if($authuser){
                            $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                        }

                        if($liked_Act){
                            $actlike = true;
                        }else{
                            $actlike = false;
                        }

            $activitiesData[] = [
                'rendom'             => $activity->rendom,
                'when_time'          => $activity->when_time,
                'end_time'           => $activity->end_time,
                'title'              => $activity->title,
                'location'           => $activity->location,
                'bg_color'           => $bgColor,
                'how_many'           => $activity->how_many,
                'like' => $actlike,
                'is_like' => false,
                'vibe_name'          => $vibeNames ?? '',
                'vibe_image'         => $vibeImages ?? '',
                'expense_name'       => $firstExpenseName ?? '',
                'user_name'          => $user->name,
                'user_profile_image' => $profileImageUrl ?? '',
                'activity_image'     => asset($activity->image),
                'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

                'status'             => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
            ];
        }

        return response()->json([
            'message' => 'Interest Activities fetched successfully',
            'status' => 200,
            'data' => $activitiesData,
        ]);
}


public function userinterestnumber(Request $request)
{
    $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $currentTime = Carbon::now('Asia/Kolkata');

            $activityIds = OtherInterest::where('user_id', $user->id)
                ->where(function($query) {
                    $query->where('confirm', 6);
                })
                ->pluck('activity_id')
                ->toArray();

    if (empty($activityIds) || count($activityIds) == 0) {
        return response()->json([
            'message' => 'No matching activities found',
            'status'  => 200,
            'data'    => [],
        ]);
    }

    $activities = Activity::whereIn('id', $activityIds)
                    ->orderBy('id', 'DESC')
                    ->get(); 

        if ($activities->isEmpty()) {
            return response()->json([
                'message' => 'No activities found',
                'status' => 200,
                'data' => [],
            ]);
        }


        $activitiesData = [];

        foreach ($activities as $activity) {

    $activityUser = User::find($activity->user_id);

            $profileImageUrl = null;
            if ($activityUser && $activityUser->profile_image) {
                $profileImages = json_decode($activityUser->profile_image, true);

                if (!empty($profileImages) && isset($profileImages[1])) {
                    $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
                }
            }
            
            // bg_color logic


            $hash = md5($activity->id);
            $r = hexdec(substr($hash, 0, 2));
            $g = hexdec(substr($hash, 2, 2));
            $b = hexdec(substr($hash, 4, 2));
            $lightenFactor = 0.5;
            $r = round($r + (255 - $r) * $lightenFactor);
            $g = round($g + (255 - $g) * $lightenFactor);
            $b = round($b + (255 - $b) * $lightenFactor);
            $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

            // Vibes
            $vibeNames = [];
            $vibeImages = [];
            $vibeIdsRaw = json_decode($activity->vibe_id, true);
            if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                $vibeIdList = explode(',', $vibeIdsRaw[0]);
                $vibes = Vibes::whereIn('id', $vibeIdList)->get();
                foreach ($vibes as $vibe) {
                    $vibeNames[] = $vibe->name;
                    $vibeImages[] = asset($vibe->icon);
                }
            }

            // Expense
            $expenseIds = json_decode($activity->expense_id, true);
            $firstExpenseName = null;
            if (is_array($expenseIds) && count($expenseIds) > 0) {
                $firstExpense = Expense::find($expenseIds[0]);
                $firstExpenseName = $firstExpense->name ?? null;
            }

            $authuser =  Auth::user();
                        if($authuser){
                            $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                        }

                        if($liked_Act){
                            $actlike = true;
                        }else{
                            $actlike = false;
                        }

            $activitiesData[] = [
                'rendom'             => $activity->rendom,
                'user_rendom'        => $activityUser->rendom,
                'when_time'          => $activity->when_time,
                'end_time'           => $activity->end_time,
                'title'              => $activity->title,
                'location'           => $activity->location,
                'bg_color'           => $bgColor,
                'how_many'           => $activity->how_many,
                'like' => $actlike,
                'is_like' => false,
                'vibe_name'          => $vibeNames ?? '',
                'vibe_image'         => $vibeImages ?? '',
                'expense_name'       => $firstExpenseName ?? '',
                'user_name'          => $activityUser->name,
                'user_profile_image' => $profileImageUrl ?? '',
                'activity_image'     => asset($activity->image),
                'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

                'status'             => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
            ];
        }

        return response()->json([
            'message' => 'Interest Activities fetched successfully',
            'status' => 200,
            'data' => $activitiesData,
        ]);
}


public function userconfirmactivitys(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $currentTime = Carbon::now('Asia/Kolkata');

$activityIds = OtherInterest::where('user_id', $user->id)
    ->where(function($query) {
        $query->where('confirm', 1)
              ->orWhere('confirm', 2)
              ->orWhere('confirm', 3)
              ->orWhere('confirm', 7);
    })
    ->pluck('activity_id')
    ->toArray();

if (empty($activityIds) || count($activityIds) == 0) {
    return response()->json([
        'message' => 'No matching activities found',
        'status'  => 200,
        'data'    => [],
    ]);
}

$activities = Activity::whereIn('id', $activityIds)
                ->orderBy('id', 'DESC')
                ->get(); 

    if ($activities->isEmpty()) {
        return response()->json([
            'message' => 'No activities found',
            'status' => 200,
            'data' => [],
        ]);
    }

    // ✅ Profile Image decode (2nd image)
    // $profileImageUrl = null;
    // if ($user->profile_image) {
    //     $profileImages = json_decode($user->profile_image, true);
    //     if (!empty($profileImages) && isset($profileImages[1])) {
    //         $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
    //     }
    // }

    $activitiesData = [];

    foreach ($activities as $activity) {

          $activityUser = User::find($activity->user_id);

        $profileImageUrl = null;
        if ($activityUser && $activityUser->profile_image) {
            $profileImages = json_decode($activityUser->profile_image, true);

            if (!empty($profileImages) && isset($profileImages[1])) {
                $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
            }
        }


        // bg_color logic
        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));
        $lightenFactor = 0.5;
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);
        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

        // Vibes
        $vibeNames = [];
        $vibeImages = [];
        $vibeIdsRaw = json_decode($activity->vibe_id, true);
        if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
            $vibeIdList = explode(',', $vibeIdsRaw[0]);
            $vibes = Vibes::whereIn('id', $vibeIdList)->get();
            foreach ($vibes as $vibe) {
                $vibeNames[] = $vibe->name;
                $vibeImages[] = asset($vibe->icon);
            }
        }

        // Expense
        $expenseIds = json_decode($activity->expense_id, true);
        $firstExpenseName = null;
        if (is_array($expenseIds) && count($expenseIds) > 0) {
            $firstExpense = Expense::find($expenseIds[0]);
            $firstExpenseName = $firstExpense->name ?? null;
        }

           $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }

        $activitiesData[] = [
            'rendom'             => $activity->rendom,
            'when_time'          => $activity->when_time,
            'end_time'           => $activity->end_time,
            'title'              => $activity->title,
            'location'           => $activity->location,
            'bg_color'           => $bgColor,
            'how_many'           => $activity->how_many,
            'like' => $actlike,
            'is_like' => false,
            'vibe_name'          => $vibeNames ?? '',
            'vibe_image'         => $vibeImages ?? '',
            'expense_name'       => $firstExpenseName ?? '',
            'user_name'          => $user->name,
            'user_profile_image' => $profileImageUrl ?? '',
            'activity_image'     => asset($activity->image),
            'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

            'status'             => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
        ];
    }

    return response()->json([
        'message' => 'Confirm Activities fetched successfully',
        'status' => 200,
        'data' => $activitiesData,
    ]);
}


public function likedactivitys(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $currentTime = Carbon::now('Asia/Kolkata');

   $activityIds = LikeActivity::where('user_id', $user->id)
                    ->where('status', 1)
                    ->pluck('activity_id')
                    ->toArray();

if (empty($activityIds) || count($activityIds) == 0) {
    return response()->json([
        'message' => 'No matching activities found',
        'status'  => 200,
        'data'    => [],
    ]);
}

$activities = Activity::whereIn('id', $activityIds)
                ->orderBy('id', 'DESC')
                ->get(); 

    if ($activities->isEmpty()) {
        return response()->json([
            'message' => 'No activities found',
            'status' => 200,
            'data' => [],
        ]);
    }

    // $profileImageUrl = null;
    // if ($user->profile_image) {
    //     $profileImages = json_decode($user->profile_image, true);
    //     if (!empty($profileImages) && isset($profileImages[1])) {
    //         $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
    //     }
    // }

    $activitiesData = [];

    foreach ($activities as $activity) {

         $activityUser = User::find($activity->user_id);

        $profileImageUrl = null;
        if ($activityUser && $activityUser->profile_image) {
            $profileImages = json_decode($activityUser->profile_image, true);

            if (!empty($profileImages) && isset($profileImages[1])) {
                $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
            }
        }

        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));
        $lightenFactor = 0.5;
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);
        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

        $vibeNames = [];
        $vibeImages = [];
        $vibeIdsRaw = json_decode($activity->vibe_id, true);
        if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
            $vibeIdList = explode(',', $vibeIdsRaw[0]);
            $vibes = Vibes::whereIn('id', $vibeIdList)->get();
            foreach ($vibes as $vibe) {
                $vibeNames[] = $vibe->name;
                $vibeImages[] = asset($vibe->icon);
            }
        }

        $expenseIds = json_decode($activity->expense_id, true);
        $firstExpenseName = null;
        if (is_array($expenseIds) && count($expenseIds) > 0) {
            $firstExpense = Expense::find($expenseIds[0]);
            $firstExpenseName = $firstExpense->name ?? null;
        }

           $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }

        $activitiesData[] = [
            'rendom'             => $activity->rendom,
            'when_time'          => $activity->when_time,
            'end_time'           => $activity->end_time,
            'title'              => $activity->title,
            'location'           => $activity->location,
            'like' => $actlike,
            'is_like' => false,
            'bg_color'           => $bgColor,
            'how_many'           => $activity->how_many,
            'vibe_name'          => $vibeNames ?? '',
            'vibe_image'         => $vibeImages ?? '',
            'expense_name'       => $firstExpenseName ?? '',
            'user_name'          => $user->name,
            'user_profile_image' => $profileImageUrl ?? '',
            'activity_image'     => asset($activity->image),
            'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

            'status'             => $activity->status == 1 ? 'pending' : ($activity->status == 2 ? 'approved' : 'unknown'),
        ];
    }

    return response()->json([
        'message' => 'Confirm Activities fetched successfully',
        'status' => 200,
        'data' => $activitiesData,
    ]);
}




public function foryouactivitys(Request $request)
{
    $users = Auth::user();
    // return $users;
    
    if (!$users) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $currentTime = Carbon::now('Asia/Kolkata'); 
    $todayDate = Carbon::today('Asia/Kolkata');  

    $activities = Activity::orderBy('id', 'DESC')
        ->where('user_id', '!=', $users->id)
        ->where('status', 2)
        ->where(function ($query) use ($currentTime) {
            $query->whereDate('when_time', '>', $currentTime->toDateString())
                ->orWhereRaw("
                    STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
                ", [$currentTime->format('Y-m-d H:i:s')]);
        })
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



    if ($activities->isEmpty()) {
        return response()->json([
            'message' => 'No upcoming activities found',
            'status'=>200,
            'data'=>[],
        ], 200);
    }

    // Process the profile image URL
  

    $activitiesData = [];
    foreach ($activities as $activity) {

         $expenseIds = json_decode($activity->expense_id, true);
    $firstExpenseName = null;
    if (is_array($expenseIds) && count($expenseIds) > 0) {
        $firstExpense = Expense::find($expenseIds[0]);
        $firstExpenseName = $firstExpense->name ?? null;
    }


         $activityUser = User::find($activity->user_id);

        $profileImageUrl = null;
        if ($activityUser && $activityUser->profile_image) {
            $profileImages = json_decode($activityUser->profile_image, true);

            if (!empty($profileImages) && isset($profileImages[1])) {
                $profileImageUrl = url('uploads/app/profile_images/' . $profileImages[1]);
            }
        }

        $hash = md5($activity->id);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $lightenFactor = 0.5;
        $r = round($r + (255 - $r) * $lightenFactor);
        $g = round($g + (255 - $g) * $lightenFactor);
        $b = round($b + (255 - $b) * $lightenFactor);

        $bgColor = sprintf('#%02x%02x%02x', $r, $g, $b);

        
                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                       $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }

        $activitiesData[] = [
            'rendom' => $activity->rendom,
            'when_time' => $activity->when_time,
            'end_time' => $activity->end_time,
            'title' => $activity->title,
            'location' => $activity->location,
            'bg_color' => $bgColor,
            'how_many' => $activity->how_many,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            'is_like' => false,
            'like' => $actlike,
            'user_name' => $activityUser->name,
            'expense_name' => $firstExpenseName,
            'user_profile_image' => $profileImageUrl ?? '',
            'activity_image' => asset($activity->image),
           'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

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

    
$userId = Auth::id();
$now = Carbon::now('Asia/Kolkata');

// Get allowed interest count
$activeSubscription = UserSubscription::where('user_id', $userId)
    ->where('type', 'Activitys')
    ->where('is_active', 1)
    ->where('activated_at', '<=', $now)
    ->where('expires_at', '>=', $now)
    ->first();

$allowedInterest = 0;

if ($activeSubscription) {
    $coinCategory = CoinCategory::find($activeSubscription->plan_id);
    $allowedInterest = $coinCategory ? $coinCategory->monthly_interests_coin : 0;
} else {
    $ActivitySubscription = ActivitySubscription::orderBy('id', 'desc')->first();
    $allowedInterest = $ActivitySubscription ? $ActivitySubscription->interests_count : 0;
}

// Calculate current interval from user's creation date
$startDate = Carbon::parse($user->created_at)->startOfDay();
$nowStartOfDay = $now->copy()->startOfDay();
$currentIntervalStart = $startDate;
$interestCount = 0;

while ($currentIntervalStart->lessThanOrEqualTo($nowStartOfDay)) {
    $currentIntervalEnd = $currentIntervalStart->copy()->addDays(30)->subSecond();

    if ($now->between($currentIntervalStart, $currentIntervalEnd)) {
        $interestCount = OtherInterest::where('user_id', $userId)
            ->whereBetween('created_at', [$currentIntervalStart, $currentIntervalEnd])
            ->count();
        break;
    }

    $currentIntervalStart = $currentIntervalEnd->copy()->addSecond();
}

$remainingInterests = max(0, $allowedInterest - $interestCount);

    // 🔹 1. Main activity from rendom
    // $mainActivity = Activity::with('user', 'vibe')->where('rendom', $request->rendom)->first();

    // $friendRendoms = explode(',', $mainActivity->friend_rendom);

    // $friends = User::whereIn('rendom', $friendRendoms)->get(['name', 'rendom']);

    // $friendNames = $friends->pluck('name');

    // $host_number = OtherInterest::where('activity_id', $mainActivity->id)->where('confirm',7)->get();

    //     $userNames = [];

    //     foreach ($host_number as $interest) {
    //         $userModel = User::find($interest->user_id);

    //         if ($userModel) {
    //             $userNames[] = $userModel->name;
    //         }
    //     }

    // $hostNames = array_unique(array_merge($friendNames, $userNames));

    $mainActivity = Activity::with('user', 'vibe')
    ->where('rendom', $request->rendom)
    ->first();

    $friendRendoms = explode(',', $mainActivity->friend_rendom);

    // Fetch friends by their random codes
    $friends = User::whereIn('rendom', $friendRendoms)->get(['id', 'name']);
    $users = collect();

    // Add friend users
    foreach ($friends as $friend) {
        $users->put($friend->id, $friend->name); // Use ID to ensure uniqueness
    }

    // Fetch hosts from OtherInterest table
    $hostInterests = OtherInterest::where('activity_id', $mainActivity->id)
        ->where('confirm', 7)
        ->get();

    foreach ($hostInterests as $interest) {
        $user = User::find($interest->user_id);
        if ($user) {
            $users->put($user->id, $user->name); // Prevent duplicate IDs
        }
    }

    // Now get all unique names
    $hostNames = $users->values()->all();

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

   $vibeNames = [];
                    $vibeImages = [];
                    $vibeid = [];

                    $vibeIdsRaw = json_decode($mainActivity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeid[] = $vibe->id;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }
    

    // Profile image
    $profileImages = json_decode($mainActivity->user->profile_image ?? '[]', true);
    $profileImageUrl = isset($profileImages[1]) ? asset('uploads/app/profile_images/' . $profileImages[1]) : null;

    // Logged-in user is creator?
    $like_user = $mainActivity->user_id === $user->id;

    // Count confirmed users
    $interestCount = OtherInterest::where('activity_id', $mainActivity->id)->count();
    $OtherInt = OtherInterest::where('user_id',Auth::id())->where('activity_id',$mainActivity->id)->first();
      if($OtherInt){
                $alinters = true;
            }else{
                $alinters = false;
            }


    $mainActivityData = [
        'user_name' => $mainActivity->user->name ?? '',
        'rendom' => $mainActivity->rendom ?? '',
        'profile_image' => $profileImageUrl ?? null,
        'activity_image' => (!empty($mainActivity->image)) ? asset($mainActivity->image): null,
        'title' => $mainActivity->title,
        'description' => $mainActivity->description,
        'location' => $mainActivity->location,
        'when_time' => $mainActivity->when_time,
        'end_time' => $mainActivity->end_time,
        'how_many' => $mainActivity->how_many,
        'interestCount' => $interestCount,
        'vibe_name' => $vibeNames,
        'vibe_image' => $vibeImages,
        'vibeid' => $vibeid,
        // 'vibe_icon' => $vibeIcons ?? '',
        'like_user' => $like_user,
        'expense_name' => $firstExpenseName,
        'already_interest' => $alinters,
        'already_update' => $mainActivity->update_count ?? 0,
        'status' => $mainActivity->status,
        'amount' => $mainActivity->amount,
        'host_name' => $hostNames,
        'remainingInterests' => $remainingInterests ?? 0,
        'create_user' => $mainActivity->user->rendom ?? 0,

    ];

    $attendees = OtherInterest::where('activity_id', $mainActivity->id)
        ->where('confirm', 3)
        // ->orWhere('confirm', 3)
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

    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');

    // $allActivities = Activity::where('id','!=',$mainActivity->id)->with('user', 'vibe')
    //     ->orderBy('id', 'desc')
    //     ->whereDate('when_time', '>=', $todayDate)
    //     ->where('user_id', '!=', $user->id)->where(function ($query) use ($todayDate, $currentTime) {
    //         $query->where(function ($subQuery) use ($todayDate, $currentTime) {
       
    //             $endTime = Carbon::createFromFormat('H:i:s', '08:28:00')->setDate($todayDate->year, $todayDate->month, $todayDate->day);
     
    //             $subQuery->where('end_time', '>=', $endTime);
    //         });
    
    //         $query->where('when_time', '>=', $currentTime);  
    //     })
    //     ->get()

   $allActivities = Activity::where('id', '!=', $mainActivity->id)
    ->with('user', 'vibe')
    ->orderBy('id', 'desc')
    ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
    ->where('user_id', '!=', $user->id)
    ->where(function ($query) use ($currentTime) {
        $query->whereRaw("
            STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
        ", [$currentTime]);

        $query->orWhere('when_time', '>=', $currentTime);
    })
    ->get()
        ->map(function ($act) {
            $images = json_decode($act->user->profile_image ?? '[]', true);
            $img = isset($images[1]) ? asset('uploads/app/profile_images/' . $images[1]) : null;

            $expenseIds = json_decode($act->expense_id, true);
            $expense = is_array($expenseIds) && count($expenseIds) > 0
                ? Expense::find($expenseIds[0])->name ?? null
                : null;

            $OtherInterest = OtherInterest::where('user_id',Auth::id())->where('activity_id',$act->id)->first();
            if($OtherInterest){
                $alinter = true;
            }else{
                $alinter = false;
            }

             $interestCount = OtherInterest::where('activity_id', $act->id)->count();

            return [
                'user_name' => $act->user->name ?? '',
                'rendom' => $act->rendom ?? '',
                'profile_image' => (!empty($act->image)) ? asset($act->image): $img,
                'title' => $act->title,
                'description' => $act->description,
                'interestCount' => $interestCount,
                'location' => $act->location,
                'when_time' => $act->when_time,
                'end_time' => $act->end_time,
                'how_many' => $act->how_many,
                'vibe_name' => $act->vibe->name ?? '',
                'vibe_icon' => $act->vibe->icon ?? '',
                'expense_name' => $expense,
                'already_interest' => $alinter,
                'status' => $act->status,
                'amount' => $act->amount,
            ];
        });

    $mainActivityData['attendees_count'] = $attendeeList->count();
    $mainActivityData['attendees'] = $attendeeList->values();

    // Merge into one flat data array
    $mergedData = array_merge([$mainActivityData]);

    $mergedData = array_map(function ($item, $index) {
        $item['serial_number'] = $index + 1;
        return $item;
    }, $mergedData, array_keys($mergedData));

    return response()->json([
        'message' => 'Activity details fetched',
        'status' => 200,
        'data' => $mainActivityData,
    ]);
}


public function foryouActivitydetailes(Request $request)
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


     
                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($mainActivity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

    // Profile image
    $profileImages = json_decode($mainActivity->user->profile_image ?? '[]', true);
    $profileImageUrl = isset($profileImages[1]) ? asset('uploads/app/profile_images/' . $profileImages[1]) : null;

    // Logged-in user is creator?
    $like_user = $mainActivity->user_id === $user->id;

    // Count confirmed users
    $interestCount = OtherInterest::where('activity_id', $mainActivity->id)->count();
    $OtherInt = OtherInterest::where('user_id',Auth::id())->where('activity_id',$mainActivity->id)->first();
      if($OtherInt){
                $alinters = true;
            }else{
                $alinters = false;
            }
    $mainActivityData = [
        'user_name' => $mainActivity->user->name ?? '',
        'rendom' => $mainActivity->rendom ?? '',
        'profile_image' => $profileImageUrl ?? null,
        'activity_image' => (!empty($mainActivity->image)) ? asset($mainActivity->image): null,
        'title' => $mainActivity->title,
        'description' => $mainActivity->description,
        'location' => $mainActivity->location,
        'when_time' => $mainActivity->when_time,
        'end_time' => $mainActivity->end_time,
        'how_many' => $mainActivity->how_many,
        'interestCount' => $interestCount,
        'vibe_name' => $vibeNames ?? '',
        'vibe_image' => $vibeImages ?? '',
        // 'vibe_icon' => $mainActivity->vibe->icon ?? '',
        'like_user' => $like_user,
        'expense_name' => $firstExpenseName,
        'already_interest' => $alinters,
        'status' => $mainActivity->status,
        'amount' => $mainActivity->amount,

    ];

    $attendees = OtherInterest::where('activity_id', $mainActivity->id)
        ->where('confirm', 3)
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

    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');

   $allActivities = Activity::where('id', '!=', $mainActivity->id)
    ->with('user', 'vibe')
    ->orderBy('id', 'desc')
    ->where('user_id', '!=', $user->id)
    ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
    ->where(function ($query) use ($currentTime) {
        $query->whereRaw("
            STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
        ", [$currentTime]);

        $query->orWhere('when_time', '>=', $currentTime);
    })
    ->get()
        ->map(function ($act) {
            $images = json_decode($act->user->profile_image ?? '[]', true);
            $img = isset($images[1]) ? asset('uploads/app/profile_images/' . $images[1]) : null;

            $expenseIds = json_decode($act->expense_id, true);
            $expense = is_array($expenseIds) && count($expenseIds) > 0
                ? Expense::find($expenseIds[0])->name ?? null
                : null;

                 $vibeNames = [];

            $vibeIdsRaw = json_decode($act->vibe_id, true); // returns: ["1,2"]
            if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                $vibeIdList = explode(',', $vibeIdsRaw[0]); // now [1, 2]

                $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                foreach ($vibes as $vibe) {
                    $vibeNames[] = trim($vibe->icon . ' ' . $vibe->name); // 🎉 Social
                }
            }

            $OtherInterest = OtherInterest::where('user_id',Auth::id())->where('activity_id',$act->id)->first();
            if($OtherInterest){
                $alinter = true;
            }else{
                $alinter = false;
            }

             $interestCount = OtherInterest::where('activity_id', $act->id)->count();

            return [
                'user_name' => $act->user->name ?? '',
                'rendom' => $act->rendom ?? '',
                // 'profile_image' => (!empty($act->image)) ? asset($act->image): $img,
                'profile_image' => $img ?? null,
                'activity_image' => (!empty($act->image)) ? asset($act->image): null,
                'title' => $act->title,
                'description' => $act->description,
                'interestCount' => $interestCount,
                'location' => $act->location,
                'when_time' => $act->when_time,
                'end_time' => $act->end_time,
                'how_many' => $act->how_many,
                'vibe_name' => $vibeNames ?? '',
                // 'vibe_icon' => $act->vibe->icon ?? '',
                'expense_name' => $expense,
                'already_interest' => $alinter,
                'status' => $act->status,
                'amount' => $act->amount,
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

   $matchingActivities = Activity::orderBy('id','DESC')
    ->where('user_id', '!=', $user->id)
    ->where('status', 2)
    ->where(function ($query) use ($interestIds) {
        foreach ($interestIds as $id) {
            $query->orWhere('interests_id', 'LIKE', '%"'.$id.'"%');
        }
    })
    ->where(function ($query) use ($currentTime) {
        $query->whereDate('when_time', '>', $currentTime->toDateString())
            ->orWhereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
            ", [$currentTime->format('Y-m-d H:i:s')]);
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

        
                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                       $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }
        return [
            // 'id' => $activity->id,
            // 'user_id' => $activity->user_id,
            'title' => $activity->title,
            'rendom' => $activity->rendom,
            'location' => $activity->location,    
            // 'image' => $imageUrl,
            'bg_color' => $activity->bg_color,
            'is_like' => false,
            'like' => $actlike,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            // 'user_id' => $userDetails->id,
            'user_name' => $userDetails->name,
            'user_profile_image' => $profileImageUrl,
            // 'user_state' => $userDetails->state,
            // 'user_city' => $userDetails->city,
           'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),


        ];
    });
        return response()->json([
            'message' => 'Matching activities found successfully',
            'status' => 200,
            'data' => $activitiesWithUserDetails,
        ]);
    }

    public function findactivity(Request $request)
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

    $matchingActivities = Activity::orderBy('id','DESC')
        ->where('user_id', '!=', $user->id)
        ->where('status', 2)
        ->where(function ($query) use ($interestIds) {
            foreach ($interestIds as $id) {
                $query->orWhere('interests_id', 'LIKE', '%"'.$id.'"%');
            }
        })
        ->where(function ($query) use ($currentTime) {
            $query->whereDate('when_time', '>', substr($currentTime, 0, 10))
                ->orWhereRaw("
                    STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
                ", [$currentTime]);
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

        
                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                       $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }

                         $expenseIds = json_decode($activity->expense_id, true);
    $firstExpenseName = null;
    if (is_array($expenseIds) && count($expenseIds) > 0) {
        $firstExpense = Expense::find($expenseIds[0]);
        $firstExpenseName = $firstExpense->name ?? null;
    }

        return [
            // 'id' => $activity->id,
            // 'user_id' => $activity->user_id,
            'title' => $activity->title,
            'rendom' => $activity->rendom,
            'location' => $activity->location,    
            // 'image' => $imageUrl,
            'bg_color' => $activity->bg_color,
            'is_like' => false,
            'like' => $actlike,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            // 'user_id' => $userDetails->id,
            'user_name' => $userDetails->name,
            'user_profile_image' => $profileImageUrl,
            'activity_image' => asset($activity->image),
            'expense_name' => $firstExpenseName,
            // 'user_state' => $userDetails->state,
            // 'user_city' => $userDetails->city,
            'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

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

    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');

    $interestIds = OtherInterest::where('user_id', $user->id)->get();
    $activityIds = $interestIds->pluck('activity_id'); 

  
    $matchingActivities = Activity::whereIn('id', $activityIds)
        ->where('user_id', '!=', $user->id)
        ->where('status', 2)
        ->where(function ($query) use ($currentTime) {
            $query->whereDate('when_time', '>', substr($currentTime, 0, 10))
                ->orWhereRaw("
                    STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
                ", [$currentTime]);
        })
        ->get();

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

         
                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }

                        $expenseIds = json_decode($activity->expense_id, true);
                    $firstExpenseName = null;
                    if (is_array($expenseIds) && count($expenseIds) > 0) {
                        $firstExpense = Expense::find($expenseIds[0]);
                        $firstExpenseName = $firstExpense->name ?? null;
                    }

                       $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }

        return [
            'title' => $activity->title,
            'rendom' => $activity->rendom,
            'location' => $activity->location, 
            'activity_image' => asset($activity->image),   
            'bg_color' => $bgColor,
            'vibe_name' => $vibeNames ?? '',
            'is_like' => false,
            'like' => $actlike,
            'vibe_image' => $vibeImages ?? '',
            'expense_name' => $firstExpenseName ?? '',
            // 'vibe_icon' => $activity->vibe->icon ?? '',
            'user_name' => $userData['name'] ?? '',
            'user_profile_image' => $userData['profile_image'] ?? '',
            'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

        ];
    });

    return response()->json([
        'message' => 'Matching activities found successfully',
        'status' => 200,
        'data' => $activitiesWithUserDetails,
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

    //     $likeUser = SlideLike::where('matched_user', $user->id);
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

    //         $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$user->id)->orderBy('id','DESC')->first();

    //         return [
    //             'id' => $user->id,
    //             'user_rendom' => $user->rendom,
    //             'name' => $user->name,
    //             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
    //             'form' => 'match',
    //             'last_message' => $chat->message ?? null,
    //         ];
    //     });

    //     $likeUserList = $likeUserDetails2->map(function ($user) {
    //         $imagePath = null;

    //         if ($user->profile_image) {
    //             $images = json_decode($user->profile_image, true); 
    //             if (is_array($images) && count($images)) {
    //                 $imagePath = reset($images);
    //             }
    //         }
    //         $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$user->id)->orderBy('id','DESC')->first();

    //         return [
    //             'id' => $user->id,
    //             'user_rendom' => $user->rendom,
    //             'name' => $user->name,
    //             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
    //             'form' => 'activity',
    //             'last_message' => $chat->message ?? null,
    //         ];
    //     });

    //     $CupidMatches = Cupid::where('user_id_1', $user->id)
    //                         ->orWhere('user_id_2', $user->id)
    //                         ->get()->unique();

    //     $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
    //         $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
    //         $matchedUser = User::find($matchedUserId);

    //         if (!$matchedUser) return null;

    //         $images = json_decode($matchedUser->profile_image, true);
    //         $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;
    //         $chat = Chat::where('sender_id',Auth::id())->where('receiver_id',$matchedUser->id)
    //         ->orderBy('id','DESC')->first();
    //         return [
    //             'id' => $matchedUser->id,
    //             'user_rendom' => $matchedUser->rendom,
    //             'name' => $matchedUser->name,
    //             'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
    //             'form' => 'match',
    //             'last_message' => $chat->message ?? null,
    //         ];
    //     })->filter(); 

    //     $userList = collect($userList);  
    //     $likeUserList = collect($likeUserList);   
    //     $matchedUsers = collect($matchedUsers);  
    //     $matchUsers = $userList->merge($likeUserList)->merge($matchedUsers);

    //     return response()->json([
    //         'message' => 'Friend and Cupid data fetched successfully',
    //         'status' => 200,
    //         'data' => [
    //             'match_users' => $matchUsers, 
    //             'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
    //             'like_count' => $interestIds->count(),
    //         ]
    //     ]);
    // }

//  public function friendcount(Request $request)
//     {
//         $user = Auth::user(); 

//         if (!$user) {
//             return response()->json(['message' => 'User not authenticated'], 401);
//         }

//         $matchingActivities = Activity::where('user_id', $user->id)
//                                         ->where('status', 2)
//                                         ->get();

//         $activityIds = $matchingActivities->pluck('id'); 

//         $interestIds = OtherInterest::whereIn('activity_id', $activityIds)->get();
//         $userDetailsFromInterest = $interestIds->pluck('user_id');

//         $likeUser = SlideLike::where('matched_user', $user->id);
//         $likeUserDetails = $likeUser->pluck('matching_user'); 

//         $userDetailsFromInterest2 = User::whereIn('id', $userDetailsFromInterest)->get();
//         $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

//         $userList = $userDetailsFromInterest2->map(function ($user) {
//             $imagePath = null;

//             if ($user->profile_image) {
//                 $images = json_decode($user->profile_image, true); 
//                 if (is_array($images) && count($images)) {
//                     $imagePath = reset($images);
//                 }
//             }

//             $chat = Chat::where('sender_id', Auth::id())->where('receiver_id', $user->id)->orderBy('id', 'DESC')->first();

//             return [
//                 'id' => $user->id,
//                 'user_rendom' => $user->rendom,
//                 'name' => $user->name,
//                 'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
//                 'form' => 'match',
//                 'last_message' => $chat->message ?? null,
//             ];
//         });

//         $likeUserList = $likeUserDetails2->map(function ($user) {
//             $imagePath = null;

//             if ($user->profile_image) {
//                 $images = json_decode($user->profile_image, true); 
//                 if (is_array($images) && count($images)) {
//                     $imagePath = reset($images);
//                 }
//             }

//             $chat = Chat::where('sender_id', Auth::id())->where('receiver_id', $user->id)->orderBy('id', 'DESC')->first();

//             return [
//                 'id' => $user->id,
//                 'user_rendom' => $user->rendom,
//                 'name' => $user->name,
//                 'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
//                 'form' => 'activity',
//                 'last_message' => $chat->message ?? null,
//             ];
//         });

//         $CupidMatches = Cupid::where('user_id_1', $user->id)
//                             ->orWhere('user_id_2', $user->id)
//                             ->get()->unique();

//         $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
//             $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
//             $matchedUser = User::find($matchedUserId);

//             if (!$matchedUser) return null;

//             $images = json_decode($matchedUser->profile_image, true);
//             $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;
//             $chat = Chat::where('sender_id', Auth::id())->where('receiver_id', $matchedUser->id)
//                         ->orderBy('id', 'DESC')->first();

//             return [
//                 'id' => $matchedUser->id,
//                 'user_rendom' => $matchedUser->rendom,
//                 'name' => $matchedUser->name,
//                 'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
//                 'form' => 'match',
//                 'last_message' => $chat->message ?? null,
//             ];
//         })->filter(); 

//         $userList = collect($userList);  
//         $likeUserList = collect($likeUserList);   
//         $matchedUsers = collect($matchedUsers);  

//         $matchUsers = $userList->merge($likeUserList)->merge($matchedUsers);

//         $matchUsers = $matchUsers->map(function ($user) {
//             $chat = Chat::where('sender_id', Auth::id())->where('receiver_id', $user['id'])->orderBy('id', 'DESC')->first();
//             if ($chat) {
//                 $user['form'] = 'activity';
//             }
//             return $user;
//         });

//         return response()->json([
//             'message' => 'Friend and Cupid data fetched successfully',
//             'status' => 200,
//             'data' => [
//                 'match_users' => $matchUsers, 
//                 'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
//                 'like_count' => $interestIds->count(),
//             ]
//         ]);
//     }


public function friendcount(Request $request)
{
    $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // 🔹 Get all activities by this user where status = 2
    $matchingActivities = Activity::where('user_id', $user->id)
                                  ->where('status', 2)
                                  ->get();

    $activityIds = $matchingActivities->pluck('id');

    $interestRelations = OtherInterest::where('user_id', $user->id)
                                      ->orWhere('user_id_1', $user->id)
                                      ->get();

    $oppositeUserIds = $interestRelations->map(function ($relation) use ($user) {
        return $relation->user_id == $user->id ? $relation->user_id_1 : $relation->user_id;
    })->unique()->values();
    

    $userDetailsFromInterest2 = User::whereIn('id', $oppositeUserIds)->get()->map(function ($userItem) use ($interestRelations, $user) {
    // Find the matching interest relation for this user
        $matchingRelation = $interestRelations->first(function ($relation) use ($userItem, $user) {
            return ($relation->user_id == $user->id && $relation->user_id_1 == $userItem->id) ||
                ($relation->user_id_1 == $user->id && $relation->user_id == $userItem->id);
        });

        // Attach activity_id to user object temporarily
        $userItem->interest_activity_id = $matchingRelation->activity_id ?? null;

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

        return [
            'id' => $userItem->id,
            'user_rendom' => $userItem->rendom,
            'name' => $userItem->name,
            'activity_id' => $userItem->interest_activity_id,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
        ];
    });

    // 🔹 Map liked users
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

    // 🔚 Final Response
    return response()->json([
        'message' => 'Friend and Cupid data fetched successfully',
        'status' => 200,
        'data' => [
            'match_users' => $matchUsers,
            'friend_count' => $matchUsers->count(),
            'like_count' => $interestRelations->count(),
        ]
    ]);
}


// public function friendcount_one(Request $request)
// {
//     $user = Auth::user(); 

//     if (!$user) {
//         return response()->json(['message' => 'User not authenticated'], 401);
//     }

//     // 🔹 Get all activities by this user where status = 2
//     $matchingActivities = Activity::where('user_id', $user->id)
//                                   ->where('status', 2)
//                                   ->get();

//     $activityIds = $matchingActivities->pluck('id');

//     // 🔹 Get opposite user IDs from OtherInterest (exclude self)
//     $interestRelations = OtherInterest::where('user_id', $user->id)
//                                       ->orWhere('user_id_1', $user->id)
//                                       ->get();

//     $oppositeUserIds = $interestRelations->map(function ($relation) use ($user) {
//         return $relation->user_id == $user->id ? $relation->user_id_1 : $relation->user_id;
//     })->unique()->values();

//     $userDetailsFromInterest2 = User::whereIn('id', $oppositeUserIds)->get()->map(function ($userItem) use ($interestRelations, $user) {
//     // Find the matching interest relation for this user
//     $matchingRelation = $interestRelations->first(function ($relation) use ($userItem, $user) {
//         return ($relation->user_id == $user->id && $relation->user_id_1 == $userItem->id) ||
//                ($relation->user_id_1 == $user->id && $relation->user_id == $userItem->id);
//     });

//         $userItem->interest_activity_id = $matchingRelation->activity_id ?? null;

//         return $userItem;
//     });

//     // 🔹 Get matched users from SlideLike table
//     $likeUser = SlideLike::where('matched_user', $user->id);
//     $likeUserDetails = $likeUser->pluck('matching_user');
//     $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

//     // 🔹 Map interest users
//     $userList = $userDetailsFromInterest2->map(function ($userItem) use ($user) {
//         $imagePath = null;
//         if ($userItem->profile_image) {
//             $images = json_decode($userItem->profile_image, true); 
//             if (is_array($images) && count($images)) {
//                 $imagePath = reset($images);
//             }
//         }

//         $chat = Chat::where('sender_id', $user->id)
//                     ->where('receiver_id', $userItem->id)
//                     ->orderBy('id', 'DESC')
//                     ->first();

//         return [
//             'id' => $userItem->id,
//             'user_rendom' => $userItem->rendom,
//             'name' => $userItem->name,
//             'activity_id' => $userItem->interest_activity_id,
//             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
//             'form' => 'activity',
//             'last_message' => $chat->message ?? null,
//         ];
//     });

//     $likeUserList = $likeUserDetails2->map(function ($userItem) use ($user) {
//         $imagePath = null;
//         if ($userItem->profile_image) {
//             $images = json_decode($userItem->profile_image, true); 
//             if (is_array($images) && count($images)) {
//                 $imagePath = reset($images);
//             }
//         }

//         $chat = Chat::where('sender_id', $user->id)
//                     ->where('receiver_id', $userItem->id)
//                     ->orderBy('id', 'DESC')
//                     ->first();

//         return [
//             'id' => $userItem->id,
//             'user_rendom' => $userItem->rendom,
//             'name' => $userItem->name,
//             'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
//             'form' => 'match',
//             'last_message' => $chat->message ?? null,
//         ];
//     });

//     // 🔹 Get Cupid matches
//     $CupidMatches = Cupid::where('user_id_1', $user->id)
//                          ->orWhere('user_id_2', $user->id)
//                          ->get()
//                          ->unique();

//     $matchedUsers = $CupidMatches->map(function ($match) use ($user) {
//         $matchedUserId = $match->user_id_1 == $user->id ? $match->user_id_2 : $match->user_id_1;
//         $matchedUser = User::find($matchedUserId);

//         if (!$matchedUser) return null;

//         $images = json_decode($matchedUser->profile_image, true);
//         $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;

//         $chat = Chat::where('sender_id', $user->id)
//                     ->where('receiver_id', $matchedUser->id)
//                     ->orderBy('id', 'DESC')
//                     ->first();

//         return [
//             'id' => $matchedUser->id,
//             'user_rendom' => $matchedUser->rendom,
//             'name' => $matchedUser->name,
//             'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
//             'form' => 'match',
//             'last_message' => $chat->message ?? null,
//         ];
//     })->filter(); // remove nulls

//     // 🔹 Combine and remove duplicates, prioritize 'match'
//     $matchUsers = collect($userList)
//                     ->merge($likeUserList)
//                     ->merge($matchedUsers)
//                     ->sortByDesc(function ($user) {
//                         return $user['form'] === 'match' ? 2 : 1;
//                     })
//                     ->unique('id')
//                     ->values();

//     // 🔚 Final Response
//     return response()->json([
//         'message' => 'Friend and Cupid data fetched successfully',
//         'status' => 200,
//         'data' => [
//             'match_users' => $matchUsers,
//             'friend_count' => $matchUsers->count(),
//             'like_count' => $interestRelations->count(),
//         ]
//     ]);
// }


public function friendcount_one(Request $request)
{
    $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // 🔹 Get all activities by this user where status = 2
    $matchingActivities = Activity::where('user_id', $user->id)
                                  ->where('status', 2)
                                  ->get();

    $activityIds = $matchingActivities->pluck('id');

    // 🔹 Get opposite user IDs from OtherInterest (exclude self)
    $interestRelations = OtherInterest::where('user_id', $user->id)
                                      ->orWhere('user_id_1', $user->id)
                                      ->get();

    $oppositeUserIds = $interestRelations->map(function ($relation) use ($user) {
        return $relation->user_id == $user->id ? $relation->user_id_1 : $relation->user_id;
    })->unique()->values();

    $userDetailsFromInterest2 = User::whereIn('id', $oppositeUserIds)->get()->map(function ($userItem) use ($interestRelations, $user) {
        $matchingRelation = $interestRelations->first(function ($relation) use ($userItem, $user) {
            return ($relation->user_id == $user->id && $relation->user_id_1 == $userItem->id) ||
                   ($relation->user_id_1 == $user->id && $relation->user_id == $userItem->id);
        });

        $userItem->interest_activity_id = $matchingRelation->activity_id ?? null;

        return $userItem;
    });

    // 🔹 Get matched users from SlideLike table
    $likeUser = SlideLike::where('matched_user', $user->id);
    $likeUserDetails = $likeUser->pluck('matching_user');
    $likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

    // 🔹 Map interest users (form: activity)
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

        $chat_message = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $userItem->id)
                    ->where('activity_id', $userItem->interest_activity_id)
                    ->orderBy('id', 'DESC')
                    ->first();

        return [
            'id' => $userItem->id,
            'user_rendom' => $userItem->rendom,
            'name' => $userItem->name,
            'activity_id' => $userItem->interest_activity_id,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
            'activity_message' => $chat_message->message ?? null,
        ];
    });

    // 🔹 Map SlideLike users (form: match) ✅ changed
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
            'form' => 'match', // ✅ changed from 'activity' to 'match'
            'last_message' => $chat->message ?? null,
        ];
    });

    // 🔹 Get Cupid matches (form: match)
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
                    ->merge($likeUserList)    // ✅ SlideLike users as 'match'
                    ->merge($matchedUsers)    // ✅ Cupid matches
                    ->sortByDesc(function ($user) {
                        return $user['form'] === 'match' ? 2 : 1;
                    })
                    ->unique('id')
                    ->values();

    // 🔚 Final Response
    return response()->json([
        'message' => 'Friend and Cupid data fetched successfully',
        'status' => 200,
        'data' => [
            'match_users' => $matchUsers,
            'friend_count' => $matchUsers->count(),
            'like_count' => $interestRelations->count(),
        ]
    ]);
}

    
    

public function filteractivity(Request $request)
{
    $location = $request->input('location');
    $when_time = $request->input('when_time');
    $end_time = $request->input('end_time');
    $expense_id = $request->input('expense_id'); 
    $vibe_id = $request->input('vibe_id'); 
    $date_type = $request->input('date_type'); 

    $currentTime = Carbon::now('Asia/Kolkata');
    $todayDate = Carbon::today('Asia/Kolkata');

    $endTime = Carbon::createFromFormat('H:i:s', '08:28:00')
        ->setDate($todayDate->year, $todayDate->month, $todayDate->day)
        ->format('Y-m-d H:i:s');

    $query = Activity::query();
    
    $filterApplied = false;

    if (!$date_type || $date_type !== 'Today') {
    $query->where(function ($query) use ($endTime, $currentTime) {
        $query->whereRaw("
            STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
        ", [$endTime])
        ->where('when_time', '>=', $currentTime);
    });
}


    if ($query) {
        $query->where('user_id', '!=',Auth::id());
        $filterApplied = true;
    }
    if ($location) {
        $query->where('location', 'like', '%' . $location . '%');
        $filterApplied = true;
    }

    if ($when_time) {
        $query->where('when_time', $when_time);
        $filterApplied = true;
    }

    if ($end_time) {
        $cleanedEndTime = preg_replace('/[^\x20-\x7E]/', '', $end_time);
        $endTimeFormatted = \Carbon\Carbon::parse($cleanedEndTime)->format('H:i:s');
        $query->whereTime('end_time', '>=', $endTimeFormatted);
        $filterApplied = true;
    }


    if ($expense_id && is_array($expense_id)) {
    $query->where(function ($q) use ($expense_id) {
        foreach ($expense_id as $id) {
            $q->orWhere('expense_id', 'like', '%'.$id.'%');
        }
    });
        $filterApplied = true;
    }


     if ($vibe_id && is_array($vibe_id)) {
        $query->where(function ($q) use ($vibe_id) {
            foreach ($vibe_id as $id) {
                $q->orWhereRaw("FIND_IN_SET(?, vibe_id)", [$id]);
            }
        });
    }


if ($date_type) {
    $today = Carbon::now('Asia/Kolkata')->format('Y-m-d');
    $tomorrow = Carbon::now('Asia/Kolkata')->addDay()->format('Y-m-d');
    $saturday = Carbon::now('Asia/Kolkata')->next(Carbon::SATURDAY)->format('Y-m-d');
    $sunday = Carbon::now('Asia/Kolkata')->next(Carbon::SUNDAY)->format('Y-m-d');

    if ($date_type == 'Today') {
        $query->where('when_time', $today);
    } elseif ($date_type == 'Tomorrow') {
        $query->where('when_time', $tomorrow);
    } elseif ($date_type == 'Weekend') {
        $query->where(function ($q) use ($saturday, $sunday) {
            $q->where('when_time', $saturday)
              ->orWhere('when_time', $sunday);
        });
    }

    $filterApplied = true;
}

    if (!$filterApplied) {
        return response()->json([
            'message' => 'No filters applied, returning all activities',
            'status' => 200,
            'data' => [],
        ], 200);
    }

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

        $vibeNames = [];
        $vibeImages = [];

        $vibeIdsRaw = json_decode($activity->vibe_id, true); 
        if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
            $vibeIdList = explode(',', $vibeIdsRaw[0]); 

            $vibes = Vibes::whereIn('id', $vibeIdList)->get();

            foreach ($vibes as $vibe) {
                $vibeNames[] = $vibe->name;
                $vibeImages[] = asset($vibe->icon);
            }
        }

        $authuser =  Auth::user();
        $actlike = false;
        if($authuser){
            $liked_Act = LikeActivity::where('activity_id', $activity->id)->where('user_id', $authuser->id)->where('status', 1)->first();
            $actlike = $liked_Act ? true : false;
        }

        return [
            'title' => $activity->title,
            'rendom' => $activity->rendom,
            'location' => $activity->location,
            'bg_color' => $bgColor,
            'is_like' => false,
            'like' => $actlike,
            'vibe_name' => $vibeNames ?? '',
            'vibe_image' => $vibeImages ?? '',
            'user_name' => $userDetails->name,
            'user_profile_image' => $profileImageUrl ?? '',
            'activity_image' => asset($activity->image),
            'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

        ];
    });

    return response()->json([
        'message' => 'Activities retrieved successfully',
        'status' => 200,
        'data' => $responseData,
    ], 200);
}

  

    // public function filteractivity(Request $request)
    // {
    //     $location = $request->input('location');
    //     $when_time = $request->input('when_time');
    //     $end_time = $request->input('end_time');
    //     $expense_id = $request->input('expense_id');  // Array e.g. ["1", "3"]
    //     $interests_id = $request->input('interests_id');  // Array e.g. ["2", "4"]

    //     $currentTime = Carbon::now('Asia/Kolkata');
    // $todayDate = Carbon::today('Asia/Kolkata');
    
    //     $endTime = Carbon::createFromFormat('H:i:s', '08:28:00')
    //         ->setDate($todayDate->year, $todayDate->month, $todayDate->day)
    //         ->format('Y-m-d H:i:s');

    //     $query = Activity::query();

    //     $query->where(function ($query) use ($endTime, $currentTime) {
    //         $query->whereRaw("
    //             STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
    //         ", [$endTime])
    //         ->where('when_time', '>=', $currentTime);
    //     });

    //     $filterApplied = false;

    //     if ($location) {
    //         $query->where('location', 'like', '%' . $location . '%');
    //         $filterApplied = true;
    //     }

    //     if ($when_time) {
    //         $query->where('when_time', $when_time);
    //         $filterApplied = true;
    //     }

    //     if ($end_time) {
    //         // Clean weird unicode spaces like ' ' (narrow no-break space)
    //         $cleanedEndTime = preg_replace('/[^\x20-\x7E]/', '', $end_time);
        
    //         // Convert to proper H:i:s
    //         $endTimeFormatted = \Carbon\Carbon::parse($cleanedEndTime)->format('H:i:s');
        
    //         $query->whereTime('end_time', '>=', $endTimeFormatted);
    //         $filterApplied = true;
    //     }
        

    //     // 🔹 Filter by expense_id (JSON string, use LIKE or FIND_IN_SET)
    //     if ($expense_id && is_array($expense_id)) {
    //         $query->where(function ($q) use ($expense_id) {
    //             foreach ($expense_id as $id) {
    //                 $q->orWhere('expense_id', 'like', '%"'.$id.'"%');
    //             }
    //         });
    //         $filterApplied = true;
    //     }

    //     // 🔹 Filter by interests_id (JSON string, use LIKE or FIND_IN_SET)
    //     if ($interests_id && is_array($interests_id)) {
    //         $query->where(function ($q) use ($interests_id) {
    //             foreach ($interests_id as $id) {
    //                 $q->orWhere('interests_id', 'like', '%"'.$id.'"%');
    //             }
    //         });
    //         $filterApplied = true;
    //     }

    //     if (!$filterApplied) {
    //         return response()->json([
    //             'message' => 'No filters applied, returning all activities',
    //             'status' => 200,
    //             'data' => [],
    //         ], 200);
    //     }

    //     // 🔹 Get filtered activities
    //     $activities = $query->with('user', 'vibe')->get();  
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

    //                              $vibeNames = [];
    //                 $vibeImages = [];

    //                 $vibeIdsRaw = json_decode($activity->vibe_id, true); 
    //                 if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
    //                     $vibeIdList = explode(',', $vibeIdsRaw[0]); 

    //                     $vibes = Vibes::whereIn('id', $vibeIdList)->get();

    //                     foreach ($vibes as $vibe) {
    //                         $vibeNames[] = $vibe->name;
    //                         $vibeImages[] = asset($vibe->icon);
    //                     }
    //                 }

    //                    $authuser =  Auth::user();
    //                 if($authuser){
    //                     $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
    //                 }

    //                 if($liked_Act){
    //                     $actlike = true;
    //                 }else{
    //                     $actlike = false;
    //                 }

    //         return [
    //             'title' => $activity->title,
    //             'rendom' => $activity->rendom,
    //             'location' => $activity->location,
    //             'bg_color' => $bgColor,
    //             'is_like' => false,
    //             'like' => $actlike,
    //             'vibe_name' => $vibeNames ?? '',
    //             'vibe_image' => $vibeImages ?? '',
    //             // 'vibe_icon' => $activity->vibe->icon ?? '',
    //             'user_name' => $userDetails->name,
    //             'user_profile_image' => $profileImageUrl ?? '',
    //         'activity_image' => asset($activity->image),
    //             'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d-F') . ' ' . \Carbon\Carbon::parse($activity->end_time)->format('H:i'),
    //         ];
    //     });


    //     return response()->json([
    //         'message' => 'Activities retrieved successfully',
    //         'status' => 200,
    //         'data' => $responseData,
    //     ], 200);
    // }


        
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
                $activityCount = Activity::where('status',2)->get();

                  $filtered = $activityCount->filter(function ($activity) use ($vibe) {
                    $vibeIdsRaw = json_decode($activity->vibe_id, true);
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $ids = explode(',', $vibeIdsRaw[0]); // e.g., "1,2" → ["1", "2"]
                        return in_array((string)$vibe->id, $ids); // Cast to string for exact match
                    }
                    return false;
                });

                $vibeWithActivityCount[] = [
                    'id' => $vibe->id,
                    'name' => $vibe->name,
                    'activity_id' => $vibe->activity_id,
                    // 'image' => $vibe->image,
                    'status' => $vibe->status,
                    'icon' => asset($vibe->icon),
                    'activity_count' => $filtered->count()
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

            // $currentTime = Carbon::now('Asia/Kolkata'); 
            $todayDate = Carbon::today('Asia/Kolkata');
           $currentTime = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');

        $activities = Activity::orderBy('id', 'DESC')
            ->where('status', 2)
            ->where('user_id', '!=', $user->id)
            ->where(function ($query) use ($currentTime) {
                $query->whereDate('when_time', '>', substr($currentTime, 0, 10))
                    ->orWhereRaw("
                        STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
                    ", [$currentTime]);
            })
            ->get()->filter(function ($activity) use ($vibe) {
        $vibeIdsRaw = json_decode($activity->vibe_id, true);
        if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
            $ids = explode(',', $vibeIdsRaw[0]); // ["1", "2"] → [1, 2]
            return in_array($vibe->id, $ids);
        }
        return false;
    });
    
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
                'image' => asset($vibe->icon),
                'icon' => $vibe->image,
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

       $currentDateTime = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');

$activities = Activity::orderBy('id', 'DESC')
    // ->where('vibe_id', 'LIKE', '%"'.$vibe->id.'"%')
    ->where('status', 2)
    ->where('user_id', '!=', $user->id)
    ->whereDate('when_time', '>=', $todayDate->format('Y-m-d'))
    ->where(function ($query) use ($currentDateTime) {
        $query->where(function ($subQuery) use ($currentDateTime) {
            $subQuery->whereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') >= ?
            ", [$currentDateTime]);
        });

        $query->orWhere('when_time', '>=', $currentDateTime);
    })
    ->get()->filter(function ($activity) use ($vibe) {
        $vibeIdsRaw = json_decode($activity->vibe_id, true);
        if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
            $ids = explode(',', $vibeIdsRaw[0]); // ["1", "2"] → [1, 2]
            return in_array($vibe->id, $ids);
        }
        return false;
    });

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
            'image' => asset($vibe->icon),
            'icon' => $vibe->image,
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

                $vibeNames = [];
                    $vibeImages = [];

                    $vibeIdsRaw = json_decode($activity->vibe_id, true); 
                    if (is_array($vibeIdsRaw) && count($vibeIdsRaw) > 0) {
                        $vibeIdList = explode(',', $vibeIdsRaw[0]); 

                        $vibes = Vibes::whereIn('id', $vibeIdList)->get();

                        foreach ($vibes as $vibe) {
                            $vibeNames[] = $vibe->name;
                            $vibeImages[] = asset($vibe->icon);
                        }
                    }


                        $expenseIds = json_decode($activity->expense_id, true);
                    $firstExpenseName = null;
                    if (is_array($expenseIds) && count($expenseIds) > 0) {
                        $firstExpense = Expense::find($expenseIds[0]);
                        $firstExpenseName = $firstExpense->name ?? null;
                    }
               $authuser =  Auth::user();
                    if($authuser){
                        $liked_Act = LikeActivity::where('activity_id',$activity->id)->where('user_id',$authuser->id)->where('status', 1)->first();
                    }

                    if($liked_Act){
                        $actlike = true;
                    }else{
                        $actlike = false;
                    }


                return [
                    'rendom' => $activity->rendom,
                    'when_time' => $activity->when_time,
                    'end_time' => $activity->end_time,
                    'title' => $activity->title,
                    'location' => $activity->location,
                    'bg_color' => $bgColor,
                    'is_like' => false,
                    'like' => $actlike,
                    'how_many' => $activity->how_many,
                    'vibe_name' => $vibeNames ?? '',
                    'vibe_image' => $vibeImages ?? '',
                    'expense_name' => $firstExpenseName ?? '',
                    // 'vibe_icon' => $activity->vibe->icon ?? '',
                    'user_name' => $user_rendom->name,
                     'user_profile_image' => $profileImageUrl ?? '',
                    'activity_image' => asset($activity->image),
                    'user_time' => \Carbon\Carbon::parse($activity->when_time)->format('d M') . ' at ' . \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),

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


 $otherInterest = OtherInterest::where('user_id', $user->id)
    ->where('activity_id', $activity_rendom_1->id)
    ->first();

if ($otherInterest && $otherInterest->confirm == 2) {
    return response()->json([
        'message' => 'You have already sent Pactup.',
        'status' => 200,
        'data' => [
            'status' => true,
        ],
    ]);
}

if ($otherInterest) {
    $otherInterest->update(['confirm' => 2]);

    return response()->json([
        'message' => 'Confirm updated successfully.',
        'status' => 200,
        'data' => [
            'status' => false,
        ],
    ]);
}

    return response()->json([
        'message' => 'No matching record in OtherInterest table.',
        'status' => 201,
        'data' => [],
], 201);
}


public function acceptnumber(Request $request)
 {
        $request->validate([
            'random' => 'required|string',
            'type' => 'required|in:accept,reject',
            'activity_rendom' => 'required|string',
        ]);

        $random = $request->input('random');
        $type = $request->input('type');
        $activity_random = $request->input('activity_rendom');

        $user = User::where('rendom', $random)->first(); // Fix spelling here
        $authUser = Auth::user();
        $activity = Activity::where('rendom', $activity_random)->first(); // Fix spelling

        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found.',
                'status' => 404,
                'data' => [],
            ], 404);
        }

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 404,
                'data' => [],
            ], 404);
        }

        $otherInterest = OtherInterest::where(function ($query) use ($user, $authUser) {
            $query->where(function($q) use ($user, $authUser) {
                $q->where('user_id', $authUser->id)
                ->where('user_id_1', $user->id);
            })->orWhere(function($q) use ($user, $authUser) {
                $q->where('user_id', $user->id)
                ->where('user_id_1', $authUser->id);
            });
        })->where('activity_id', $activity->id)->first();

        if ($otherInterest) {
            $otherInterest->update([
                'confirm' => $type === 'accept' ? 7 : 4
            ]);

            return response()->json([
                'message' => 'Confirmation updated successfully.',
                'status' => 200,
                'data' => [
                    'status' => true,
                ],
            ], 200);
        }

        return response()->json([
            'message' => 'No matching record in OtherInterest table.',
            'status' => 404,
            'data' => [
                'status' => false,
            ],
        ], 404);
 }


public function acceptpactup(Request $request)
{
    $request->validate([
        'random' => 'required|string',  
    ]);

    $random = $request->input('random');
    $pactup = $request->input('type');
    $activity_id = $request->input('activity_id');
    // return $activity_id;



    $user = User::where('rendom', $random)->first();
    $user_auth = Auth::user();
    // $activity_id = Activity::where('rendom', $activity_rendom)->first();

  


    if($pactup == null){

        $otherInterest = OtherInterest::where('user_id', $user_auth->id)
            ->Where('user_id_1', $user->id)->where('confirm', 2)
        ->get();

        
       $data = [];

        foreach ($otherInterest as $interest) {
            $activity = Activity::find($interest->activity_id);

            $data[] = [
                'user_name' => $user->name,
                'activity_title' => $activity->title ?? null,
                'activity_id' => $activity->id ?? null,
            ];
        }

        if($data){
        return response()->json([
            'message' => 'Data fetched successfully.',
            'status' => 200,
            'data' => $data,
        ], 200);
        }else{
            return response()->json([
                'message' => 'No data found.',
                'status' => 201,
                'data' => [],
            ], 201);
        }

    }else{

          if (!$activity_id) {
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


    //  $otherInterest = OtherInterest::where(function($query) use ($user,$user_auth)  {
    //     $query->where('user_id', $user_auth->id)
    //         ->orWhere('user_id_1', $user->id);
    //     })->where('confirm', 2)->where('activity_id', $activity_id)
    //     ->first();
     $otherInterest = OtherInterest::where('user_id', $user_auth->id)
            ->Where('user_id_1', $user->id)->where('confirm', 2)->where('activity_id', $activity_id)
        ->first();

    // $otherInterest = OtherInterest::where(function ($query) use ($user, $user_auth) {
    //     $query->where(function ($q) use ($user_auth, $user) {
    //         $q->where('user_id', $user_auth->id)
    //         ->where('user_id_1', $user->id);
    //     })->orWhere(function ($q) use ($user_auth, $user) {
    //         $q->where('user_id', $user->id)
    //         ->where('user_id_1', $user_auth->id);
    //     });
    // })->where('confirm', 2)
    // ->where('activity_id', $activity_id)
    // ->first();

    if ($otherInterest) {
        if($pactup == 'accept'){
         $otherInterest->update(['confirm' => 3]);
        }else{
         $otherInterest->update(['confirm' => 4]);
        }
        return response()->json([
            'message' => 'Confirm updated successfully to',
            'status' => 200,
            'data' => [
                'status' => true,
            ],
        ], 200);
    }
    }

    return response()->json([
        'message' => 'No matching record in OtherInterest table.',
        'status' => 201,
        'data' => [
             'status' => false,
        ],
], 201);
}



public function pactup_request(Request $request)
{
    $user = Auth::user(); 

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // 🔹 Get all activities by this user where status = 2
    $matchingActivities = Activity::where('user_id', $user->id)
                                  ->where('status', 2)
                                  ->get();

    $activityIds = $matchingActivities->pluck('id');

    // 🔹 Get opposite user IDs from OtherInterest (exclude self)
    $interestRelations = OtherInterest::where('user_id', $user->id)
                                      ->orWhere('user_id_1', $user->id)
                                      ->get();

    $oppositeUserIds = $interestRelations->map(function ($relation) use ($user) {
        return $relation->user_id == $user->id ? $relation->user_id_1 : $relation->user_id;
    })->unique()->values();

    $userDetailsFromInterest2 = User::whereIn('id', $oppositeUserIds)->get()->map(function ($userItem) use ($interestRelations, $user) {
    // Find the matching interest relation for this user
    $matchingRelation = $interestRelations->first(function ($relation) use ($userItem, $user) {
        return ($relation->user_id == $user->id && $relation->user_id_1 == $userItem->id) ||
               ($relation->user_id_1 == $user->id && $relation->user_id == $userItem->id);
    });

        $userItem->interest_activity_id = $matchingRelation->activity_id ?? null;

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

        return [
            'id' => $userItem->id,
            'user_rendom' => $userItem->rendom,
            'name' => $userItem->name,
            'activity_id' => $userItem->interest_activity_id,
            'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
            'form' => 'activity',
            'last_message' => $chat->message ?? null,
        ];
    });

    // 🔹 Map liked users
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

    // 🔚 Final Response
    return response()->json([
        'message' => 'Friend and Cupid data fetched successfully',
        'status' => 200,
        'data' => $userList
    ]);
}




    public function admincity(){

        $cities = AdminCity::all()->map(function($city) {
            return [
                'id' => $city->id,
                'city_name' => $city->city_name,
                'status' => $city->status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);

    }

}

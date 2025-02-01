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
use App\Models\ActivityTemp;
use App\Models\OtherInterest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class SubscriptionController extends Controller
{
  
    public function subscriptionlist(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        
        $currentTime = Carbon::now('Asia/Kolkata'); 
        $todayDate = Carbon::today('Asia/Kolkata');
        
        // Get all activities for the user
        $activities = Activity::where('user_id', $user->id)->where('status',2) 
            ->where(function ($query) use ($todayDate, $currentTime) {
                $query->where('when_time', '>=', $todayDate)
                    ->where('end_time', '>=', $currentTime);
            })
            ->get();
        
        // Check if activities exist
        if ($activities->isEmpty()) {
            return response()->json(['message' => 'No upcoming activities found'], 200);
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
                'id' => $activity->id,
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
            'data' => $activitiesData, 
        ]);
    }
 


}

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
use App\Models\Chat;
use App\Models\Cupid;
use App\Models\Vibes;
use App\Models\Activity;
use App\Models\OtherInterest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class DatingController extends Controller
{

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
    
        $userLatitude = $user->latitude;  
        $userLongitude = $user->longitude;
    
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
        $totalMatchingUsers = 0; 
    
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
    
                $matchingInterestCount = 0;
                foreach ($userInterests as $interest) {
                    if (in_array($interest->id, $interestIds)) {
                        $matchingInterestCount++;
                    }
                }
    
                $totalInterests = count($interestIds);

                $matchingPercentage = ($totalInterests > 0) ? ($matchingInterestCount / $totalInterests) * 100 : 0;
    
                $profileImages = json_decode($matchingUser->profile_image, true);
                $profileImageUrl = null;
    
                if (!empty($profileImages) && isset($profileImages[1])) {
                    $profileImageUrl = asset('uploads/app/profile_images/' . $profileImages[1]);
                }
    
                $matchedUserLatitude = $matchingUser->latitude;
                $matchedUserLongitude = $matchingUser->longitude;
    
                $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

                $message = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $matchingUser->id)
                ->latest()
                ->first();

                $usersWithInterests[] = [
                    // 'user' => [
                        'id' => $matchingUser->id,
                        'name' => $matchingUser->name,
                        'age' => $matchingUser->age,
                        'gender' => $matchingUser->gender,
                        'looking_for' => $matchingUser->looking_for,
                        'profile_image' => $profileImageUrl,
                        'status' => $matchingUser->status,
                        'match_percentage' => number_format($matchingPercentage, 2),
                        'distance' => $distance . ' km', 
                        'message' => $message ? $message->message : null, 
                        'message_status' => $message ? $message->status : null, 
                    // ],
                ];
                $totalMatchingUsers++;
            }
        }
    
        return response()->json([
            'message' => 'Matching users found successfully',
            'status' => 200,
            'total_count' => $totalMatchingUsers, 
            'data' => $usersWithInterests,
        ]);
    }
    

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // Earth radius in kilometers

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
        cos($latFrom) * cos($latTo) *
        sin($lonDelta / 2) * sin($lonDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; 
    return round($distance, 2);
}


//     public function findMatchingUsers(Request $request)
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

//     $matchingUsers = User::where(function ($query) use ($interestIds) {
//         foreach ($interestIds as $interestId) {
//             $query->orWhere('interest', 'like', "%$interestId%");
//         }
//     })
//     ->where('id', '!=', $user->id)
//     ->get();

//     if ($matchingUsers->isEmpty()) {
//         return response()->json(['message' => 'No matching users found'], 404);
//     }

//     $usersWithInterests = [];

//     foreach ($matchingUsers as $matchingUser) {
//         $userInterestsField = $matchingUser->interest;
//         $userInterestsDecoded = json_decode($userInterestsField, true);

//         if (is_array($userInterestsDecoded)) {
//             $userInterestsIds = [];
//             foreach ($userInterestsDecoded as $item) {
//                 $userInterestsIds = array_merge($userInterestsIds, explode(',', $item));
//             }

//             $userInterestsIds = array_map('trim', $userInterestsIds);
//             $userInterests = Interest::whereIn('id', $userInterestsIds)->get();

//             // Calculate the matching interests
//             $matchingInterestCount = 0;
//             foreach ($userInterests as $interest) {
//                 if (in_array($interest->id, $interestIds)) {
//                     $matchingInterestCount++;
//                 }
//             }
            
//             // Total interests the authenticated user has
//             $totalInterests = count($interestIds);
            
//             // Calculate match percentage
//             $matchingPercentage = ($totalInterests > 0) ? ($matchingInterestCount / $totalInterests) * 100 : 0;

//             $formattedInterests = $userInterests->map(function ($interest) {
//                 return [
//                     'id' => $interest->id,
//                     'name' => $interest->name,
//                     'icon' => asset('uploads/app/int_images/' . $interest->icon),
//                     'status' => $interest->status,
//                     'desc' => $interest->desc,
//                     'created_at' => $interest->created_at,
//                     'updated_at' => $interest->updated_at,
//                     'deleted_at' => $interest->deleted_at,
//                 ];
//             });

//             // Generate the profile image URLs for the user
//             $profileImages = json_decode($matchingUser->profile_image, true);
//             $profileImageUrls = [];
//             if ($profileImages) {
//                 foreach ($profileImages as $image) {
//                     $profileImageUrls[] = asset('uploads/app/profile_images/' . $image);
//                 }
//             }

//             // Add the match percentage to the user data
//             $usersWithInterests[] = [
//                 'user' => [
//                     'id' => $matchingUser->id,
//                     'number' => $matchingUser->number,
//                     'auth' => $matchingUser->auth,
//                     'name' => $matchingUser->name,
//                     'email' => $matchingUser->email,
//                     'age' => $matchingUser->age,
//                     'gender' => $matchingUser->gender,
//                     'looking_for' => $matchingUser->looking_for,
//                     'interest' => $matchingUser->interest,
//                     'profile_image' => $profileImageUrls,
//                     'status' => $matchingUser->status,
//                     'match_percentage' => $matchingPercentage, 
//                 ],
//                 'interests' => $formattedInterests,
//             ];
//         }
//     }

//     return response()->json([
//         'message' => 'Matching users found successfully',
//         'status' => 200,
//         'data' => $usersWithInterests,
//     ]);
// }


public function cupidmatch(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'message' => 'User not authenticated',
            'status' => 401
        ], 401);
    }

    $validated = $request->validate([
        'user_id_1' => 'required|exists:users,id', 
        'user_id_2' => 'required|exists:users,id',
        'accept' => 'nullable|boolean',
        'decline' => 'nullable|boolean',
        'message' => 'nullable|string|max:500',
        'identity' => 'nullable|string|max:255',
    ]);

    $maker_id = Auth::id();

    try {

        $cupid = new Cupid([
            'user_id_1' => $request->user_id_1,
            'user_id_2' => $request->user_id_2,
            'maker_id' => $maker_id,
            'accept' => $request->accept, null,
            'decline' => $request->decline, null,
            'message' => $request->message, null,
            'identity' => $request->identity,
        ]);

        $cupid->save();
        $cupid->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'message' => 'Cupid match saved successfully!',
            'status' => 200,
            'data' => $cupid
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error saving cupid match.',
            'error' => $e->getMessage(),
            'status' => 500
        ], 500);
    }
}


    public function updateCupidMatch(Request $request)
        {
            
            if (!Auth::check()) {
                return response()->json([
                    'message' => 'User not authenticated',
                    'status' => 401
                ], 401);
            }

            $validated = $request->validate([
                'cupid_id' => 'required|exists:cupid_match,id',  
                'accept' => 'nullable|boolean',  
                'decline' => 'nullable|boolean', 
            ]);

            try {
                $cupid = Cupid::find($request->cupid_id);

                if ($request->has('accept')) {
                    $cupid->accept = $request->accept;
                }

                if ($request->has('decline')) {
                    $cupid->decline = $request->decline;
                }
                $cupid->save();

                $cupid->makeHidden(['created_at', 'updated_at', 'deleted_at']);

                return response()->json([
                    'message' => 'Cupid match updated successfully!',
                    'status' => 200,
                    'data' => $cupid
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error updating cupid match.',
                    'error' => $e->getMessage(),
                    'status' => 500
                ], 500);
            }
        }

}

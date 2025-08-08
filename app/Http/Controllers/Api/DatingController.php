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
use App\Models\SlideLike;
use App\Models\OtherInterest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class DatingController extends Controller
{

    // public function findMatchingUsers(Request $request)
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
    
    //     $userLatitude = $user->latitude;  
    //     $userLongitude = $user->longitude;
    
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
    //     $totalMatchingUsers = 0; 
    
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
    
    //             $matchingInterestCount = 0;
    //             foreach ($userInterests as $interest) {
    //                 if (in_array($interest->id, $interestIds)) {
    //                     $matchingInterestCount++;
    //                 }
    //             }
    
    //             $totalInterests = count($interestIds);

    //             $matchingPercentage = ($totalInterests > 0) ? ($matchingInterestCount / $totalInterests) * 100 : 0;
    
    //             $profileImages = json_decode($matchingUser->profile_image, true);
    //             $profileImageUrl = null;
    
    //             if (!empty($profileImages) && isset($profileImages[1])) {
    //                 $profileImageUrl = asset('uploads/app/profile_images/' . $profileImages[1]);
    //             }
    
    //             $matchedUserLatitude = $matchingUser->latitude;
    //             $matchedUserLongitude = $matchingUser->longitude;
    
    //             $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

    //             $message = Chat::where('sender_id', Auth::id())
    //             ->where('receiver_id', $matchingUser->id)
    //             ->latest()
    //             ->first();

    //             $usersWithInterests[] = [
    //                 // 'user' => [
    //                     // 'id' => $matchingUser->id,
    //                     'user' => $matchingUser->name,
    //                     'user_rendom' => $matchingUser->rendom,
    //                     'age' => $matchingUser->age,
    //                     'gender' => $matchingUser->gender,
    //                     'looking_for' => $matchingUser->looking_for,
    //                     'user_profile' => $profileImageUrl,
    //                     'status' => $matchingUser->status,
    //                     'match_percentage' => number_format($matchingPercentage, 2),
    //                     'distance' => $distance . ' km', 
    //                     'message' => $message ? $message->message : null, 
    //                     'message_status' => $message ? $message->status : null, 
    //                 // ],
    //             ];
    //             $totalMatchingUsers++;
    //         }
    //     }
    
    //     return response()->json([
    //         'message' => 'Matching users found successfully',
    //         'status' => 200,
    //         'total_count' => $totalMatchingUsers, 
    //         'data' => $usersWithInterests,
    //     ]);
    // }


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

    // Get filters from request
    $dateRange = $request->input('date_range'); // Example: '18-27'
    $lookingFor = $request->input('looking_for');
    $maxDistance = $request->input('distance'); // in km

    // Parse date range if provided
    $minAge = $maxAge = null;
    if ($dateRange) {
        $dateRangeParts = explode('-', $dateRange);
        $minAge = $dateRangeParts[0] ?? null;
        $maxAge = $dateRangeParts[1] ?? null;
    }

    // Modify the query to add conditions for the filters
    $matchingUsers = User::where(function ($query) use ($interestIds) {
        foreach ($interestIds as $interestId) {
            $query->orWhere('interest', 'like', "%$interestId%");
        }
    })
    ->where('id', '!=', $user->id)
    ->when($minAge && $maxAge, function ($query) use ($minAge, $maxAge) {
        return $query->whereBetween('age', [$minAge, $maxAge]);
    })
    ->when($lookingFor, function ($query) use ($lookingFor) {
        return $query->where('looking_for', 'like', "%$lookingFor%");
    })
    ->get();
    // return $matchingUsers;

    if ($matchingUsers->isEmpty()) {
        return response()->json([
        'message' => 'No matching users found',
        'status' => 201,
        'data' => [],
    ], 200);
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

            // Calculate distance between users, always calculate distance
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

            // Apply the distance filter if provided
            if ($maxDistance && $distance > $maxDistance) {
                continue; // Skip users who are farther than the specified distance
            }

            // Add user data to response array
            $message = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $matchingUser->id)
                ->latest()
                ->first();

            $userData = [
                'user' => $matchingUser->name,
                'user_rendom' => $matchingUser->rendom,
                'age' => $matchingUser->age,
                'gender' => $matchingUser->gender,
                'looking_for' => $matchingUser->looking_for,
                'user_profile' => $profileImageUrl,
                'status' => $matchingUser->status,
                'address' => $matchingUser->address,
                'match_percentage' => number_format($matchingPercentage, 2),
                'message' => $message ? $message->message : null,
                'message_status' => $message ? $message->status : null,
                'distance' => round($distance) . ' km', 
            ];

            $usersWithInterests[] = $userData;
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

    public function findswipe(Request $request)
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

        $dateRange = $request->input('date_range');
        $lookingFor = $request->input('looking_for');
        $maxDistance = $request->input('distance'); 

        $minAge = $maxAge = null;
        if ($dateRange) {
            $dateRangeParts = explode('-', $dateRange);
            $minAge = $dateRangeParts[0] ?? null;
            $maxAge = $dateRangeParts[1] ?? null;
        }

        $matchingUsers = User::where(function ($query) use ($interestIds) {
            foreach ($interestIds as $interestId) {
                $query->orWhere('interest', 'like', "%$interestId%");
            }
        })
        ->where('id', '!=', $user->id)
        ->when($minAge && $maxAge, function ($query) use ($minAge, $maxAge) {
            return $query->whereBetween('age', [$minAge, $maxAge]);
        })
        ->when($lookingFor, function ($query) use ($lookingFor) {
            return $query->where('looking_for', 'like', "%$lookingFor%");
        })
        ->get();

        if ($matchingUsers->isEmpty()) {
            return response()->json([
            'message' => 'No matching users found',
            'status' => 201,
            'data' => [],
        ], 200);
        }

        $usersWithInterests = [];
        $totalMatchingUsers = 0;

        foreach ($matchingUsers as $matchingUser) {
            $alreadyLiked = SlideLike::where('matched_user', $user->id)
            ->where('matching_user', $matchingUser->id)
             ->exists();
            // $alreadyLiked = SlideLike::where('matching_user', $user->id)
            // ->where('matched_user', $matchingUser->id)
            //  ->exists();
            if ($alreadyLiked) {
                continue;
            }
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
                $profileImageUrls = [];

                if (is_array($profileImages)) {
                    foreach ($profileImages as $image) {
                        $profileImageUrls[] = asset('uploads/app/profile_images/' . $image);
                    }
}

                $matchedUserLatitude = $matchingUser->latitude;
                $matchedUserLongitude = $matchingUser->longitude;

                // Calculate distance between users, always calculate distance
                $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

                // Apply the distance filter if provided
                if ($maxDistance && $distance > $maxDistance) {
                    continue; // Skip users who are farther than the specified distance
                }

                // Add user data to response array
                $message = Chat::where('sender_id', Auth::id())
                    ->where('receiver_id', $matchingUser->id)
                    ->latest()
                    ->first();

                $userData = [
                    'user' => $matchingUser->name,
                    'user_rendom' => $matchingUser->rendom,
                    'about' => $matchingUser->about,
                    'interest' => $userInterests,
                    'age' => $matchingUser->age,
                    'gender' => $matchingUser->gender,
                    'looking_for' => $matchingUser->looking_for,
                    'user_profile' => $profileImageUrls,
                    'status' => $matchingUser->status,
                    'address' => $matchingUser->address,
                    'match_percentage' => number_format($matchingPercentage, 2),
                    'message' => $message ? $message->message : null,
                    'message_status' => $message ? $message->status : null,
                    'distance' => round($distance) . ' km', 
                ];

                $usersWithInterests[] = $userData;
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

    





     public function MatchingUsersdetailes(Request $request)
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

    

    $attendUsers = OtherInterest::where('user_id', $user->id)
        ->where('confirm', 6)
        ->count();

    $ghostUsers = OtherInterest::where('user_id', $user->id)
        ->where('confirm', 3)
        ->count();

    $hostedActivity = Activity::where('user_id', $user->id)
        ->count();

    $interestIds = [];
    foreach ($interestFieldDecoded as $item) {
        $interestIds = array_merge($interestIds, explode(',', $item));
    }

    $interestIds = array_map('trim', $interestIds);

    $userLatitude = $user->latitude;
    $userLongitude = $user->longitude;

    // Get filters from request
    $dateRange = $request->input('date_range'); // Example: '18-27'
    $lookingFor = $request->input('looking_for');
    $maxDistance = $request->input('distance'); // in km

    // Parse date range if provided
    $minAge = $maxAge = null;
    if ($dateRange) {
        $dateRangeParts = explode('-', $dateRange);
        $minAge = $dateRangeParts[0] ?? null;
        $maxAge = $dateRangeParts[1] ?? null;
    }

    // Modify the query to add conditions for the filters
  $excludedUserIds = SlideLike::where('matched_user', $user->id)
    ->where('liked_user', 1)
    ->pluck('matching_user') // Assuming `matching_user` is the other user's ID
    ->toArray();

    // Step 2: Filter matching users, excluding matched ones
    $matchingUsers = User::where(function ($query) use ($interestIds) {
        foreach ($interestIds as $interestId) {
            $query->orWhere('interest', 'like', "%$interestId%");
        }
    })
    ->where('id', '!=', $user->id)
    ->whereNotIn('id', $excludedUserIds) // ✅ Exclude already matched users
    ->when($minAge && $maxAge, function ($query) use ($minAge, $maxAge) {
        return $query->whereBetween('age', [$minAge, $maxAge]);
    })
    ->when($lookingFor, function ($query) use ($lookingFor) {
        return $query->where('looking_for', 'like', "%$lookingFor%");
    })
    ->get();
    // return $matchingUsers;

    if ($matchingUsers->isEmpty()) {
        return response()->json([
        'message' => 'No matching users found',
        'status' => 201,
        'data' => [],
    ], 200);
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
$profileImageUrls = [];

if (is_array($profileImages)) {
    foreach ($profileImages as $image) {
        $profileImageUrls[] = asset('uploads/app/profile_images/' . $image);
    }
}
            $matchedUserLatitude = $matchingUser->latitude;
            $matchedUserLongitude = $matchingUser->longitude;

            // Calculate distance between users, always calculate distance
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

            // Apply the distance filter if provided
            if ($maxDistance && $distance > $maxDistance) {
                continue; // Skip users who are farther than the specified distance
            }

            // Add user data to response array
            $message = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $matchingUser->id)
                ->latest()
                ->first();

            $userData = [
                'user' => $matchingUser->name,
                'user_rendom' => $matchingUser->rendom,
                'about' => $matchingUser->about,
                'interest' => $userInterests,
                'age' => $matchingUser->age,
                'gender' => $matchingUser->gender,
                'looking_for' => $matchingUser->looking_for,
                'user_profile' => $profileImageUrls,
                'status' => $matchingUser->status,
                'address' => $matchingUser->address,
                'match_percentage' => number_format($matchingPercentage, 2),
                'message' => $message ? $message->message : null,
                'message_status' => $message ? $message->status : null,
                'distance' => round($distance) . ' km', 
                'attendUsers' => $attendUsers,
                'ghostUsers' => $ghostUsers,
                'hostedActivity' => $hostedActivity,
            ];

            $usersWithInterests[] = $userData;
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




public function datingpreference(Request $request)
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

    $attendUsers = OtherInterest::where('user_id', $user->id)->where('confirm', 6)->count();
    $ghostUsers = OtherInterest::where('user_id', $user->id)->where('confirm', 3)->count();
    $hostedActivity = Activity::where('user_id', $user->id)->count();

    $interestIds = [];
    foreach ($interestFieldDecoded as $item) {
        $interestIds = array_merge($interestIds, explode(',', $item));
    }
    $interestIds = array_map('trim', $interestIds);

    $userLatitude = $user->latitude;
    $userLongitude = $user->longitude;

    // Get filters from request
    $dateRange = $request->input('date_range'); // "18-30"
    $genderFilter = $request->input('gender');  // "Male", "Female", "Other"
    $lookingFor = $request->input('looking_for');
    $maxDistance = $request->input('distance'); // in km

    // Parse date range
    $minAge = $maxAge = null;
    if ($dateRange) {
        $dateRangeParts = explode('-', $dateRange);
        $minAge = isset($dateRangeParts[0]) ? (int)$dateRangeParts[0] : null;
        $maxAge = isset($dateRangeParts[1]) ? (int)$dateRangeParts[1] : null;
    }

    // Already matched users
    $excludedUserIds = SlideLike::where('matched_user', $user->id)
        ->where('liked_user', 1)
        ->pluck('matching_user')
        ->toArray();

    // Main query
    $matchingUsers = User::where(function ($query) use ($interestIds) {
            foreach ($interestIds as $interestId) {
                $query->orWhere('interest', 'like', "%$interestId%");
            }
        })
        ->where('id', '!=', $user->id)
        ->whereNotIn('id', $excludedUserIds)
        ->when($minAge && $maxAge, fn($query) => $query->whereBetween('age', [$minAge, $maxAge]))
        ->when($lookingFor, fn($query) => $query->where('looking_for', 'like', "%$lookingFor%"))
        ->when($genderFilter, fn($query) => $query->where('gender', $genderFilter))
        ->get();

    if ($matchingUsers->isEmpty()) {
        return response()->json([
            'message' => 'No matching users found',
            'status' => 201,
            'data' => [],
        ], 200);
    }

    $usersWithInterests = [];
    $totalMatchingUsers = 0;

    foreach ($matchingUsers as $matchingUser) {
        $userInterestsDecoded = json_decode($matchingUser->interest, true);

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

            // Profile images
            $profileImages = json_decode($matchingUser->profile_image, true);
            $profileImageUrls = [];
            if (is_array($profileImages)) {
                foreach ($profileImages as $image) {
                    $profileImageUrls[] = asset('uploads/app/profile_images/' . $image);
                }
            }

            // Distance calculation
            $matchedUserLatitude = $matchingUser->latitude;
            $matchedUserLongitude = $matchingUser->longitude;
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedUserLatitude, $matchedUserLongitude);

            if ($maxDistance && $distance > $maxDistance) {
                continue;
            }

            // Latest message
            $message = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $matchingUser->id)
                ->latest()
                ->first();

            $userData = [
                'user' => $matchingUser->name,
                'user_rendom' => $matchingUser->rendom,
                'about' => $matchingUser->about,
                'interest' => $userInterests,
                'age' => $matchingUser->age,
                'gender' => $matchingUser->gender,
                'looking_for' => $matchingUser->looking_for,
                'user_profile' => $profileImageUrls,
                'status' => $matchingUser->status,
                'address' => $matchingUser->address,
                'match_percentage' => number_format($matchingPercentage, 2),
                'message' => $message?->message,
                'message_status' => $message?->status,
                'distance' => round($distance) . ' km',
                'attendUsers' => $attendUsers,
                'ghostUsers' => $ghostUsers,
                'hostedActivity' => $hostedActivity,
            ];

            $usersWithInterests[] = $userData;
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
        'user_1_rendom' => 'required',
        'user_2_rendom' => 'required',
        'accept' => 'nullable|boolean',
        'decline' => 'nullable|boolean',
        'message' => 'nullable|string|max:500',
        'identity' => 'nullable|string|max:255',
    ]);

    $maker_id = Auth::id();

    try {

        $rendom_1 = User::where('rendom', $request->user_1_rendom)->first();
        $rendom_2 = User::where('rendom', $request->user_2_rendom)->first();

        if (!$rendom_1 || !$rendom_2) {
            return response()->json([
                'message' => 'User not found',
                'data' => [],
                'status' => 200,
        ], 404);
        }


        // $randomNumber = rand(100000, 999999);

        do {
            $randomNumber = rand(100000, 999999);
        } while (Cupid::where('rendom', $randomNumber)->exists());
        
        $cupid = new Cupid([
            'user_id_1' => $rendom_1->id,
            'user_id_2' => $rendom_2->id,
            'maker_id' => $maker_id,
            'accept' => $request->accept ?? null,
            'decline' => $request->decline ?? null,
            'message' => $request->message ?? null,
            'identity' => $request->identity ?? null,
            'rendom' => $randomNumber,
            'status' => 0,
        ]);

        // Save the Cupid match
        $cupid->save();


        $maker_rendom = User::where('id',$cupid->maker_id)->first();


        // Prepare a new array for the response without sensitive information
        $response = [
            'message' => 'Cupid match saved successfully!',
            'status' => 200,
            'data' => [
                'user_1_rendom' => $rendom_1->rendom,
                'user_2_rendom' => $rendom_2->rendom,
                'accept' => $cupid->accept,
                'decline' => $cupid->decline,
                'message' => $cupid->message,
                'identity' => $cupid->identity,
                'maker_rendom' => $maker_rendom->rendom,
                'rendom' => $cupid->rendom,
                'status' => 0,
                // You can add any other necessary fields you want to return in the response
            ]
        ];

        return response()->json([
            'message' => '',
            'data' => $response,
            'status' => 200,
        ],200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error saving cupid match.',
            'error' => $e->getMessage(),
            'status' => 500
        ], 500);
    }
}



public function cupidMatchFriend(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'User not authenticated',
            'status' => 401
        ], 401);
    }

    $maker = Auth::user();

    $CupidMatches = Cupid::where('user_id_1', $maker->id)
                    ->orWhere('user_id_2', $maker->id)
                    ->get();

    $matchedUsers = $CupidMatches->map(function ($match) use ($maker) {
        $matchedUserId = $match->user_id_1 == $maker->id ? $match->user_id_2 : $match->user_id_1;

        $user = User::find($matchedUserId);

        if (!$user) return null; 

        $images = json_decode($user->profile_image, true);
        $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'profile_image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
            'status' => $match->status,
            'message' => $match->message,
            'time' => Carbon::parse($match->created_at)->diffForHumans(),
            'status_id' => $match->id
        ];
    })->filter(); 

    return response()->json([
        'message' => 'Cupid matches found successfully!',
        'status' => 200,
        'data' => $matchedUsers->values()
    ], 200);
}



public function acceptCupid(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'User not authenticated',
            'status' => 401
        ], 401);
    }

    // Validate request data
    $request->validate([
        'status' => 'required',
        'user_id' => 'required',
    ]);

    $maker = Auth::user();

    $cupid = Cupid::where('id', $request->user_id)->first();

    if (!$cupid) {
        return response()->json([
            'message' => 'Cupid match not found or not authorized',
            'status' => 404
        ], 404);
    }

    $cupid->status = $request->status;
    $cupid->save();

    return response()->json([
        'message' => 'Cupid match status updated successfully!',
        'status' => 200,
        'data' => [
            'id' => $cupid->id,
            'status' => $cupid->status
        ]
    ], 200);
}



// public function cupidMatchFriend(Request $request)
// {
//     if (!Auth::check()) {
//         return response()->json([
//             'message' => 'User not authenticated',
//             'status' => 401
//         ], 401);
//     }

//     $maker = Auth::user();

//     $Cupid = Cupid::where('user_id_1', $maker->id)
//                 ->orWhere('user_id_2', $maker->id)
//                 ->get();

//     $userIds = $Cupid->flatMap(function ($item) {
//         return [$item->user_id_1, $item->user_id_2];
//     })->unique()->filter(function ($id) use ($maker) {
//         return $id != $maker->id;
//     });

//     $users = User::whereIn('id', $userIds)->get(['id', 'name', 'profile_image']);


//     $users = $users->map(function ($user) {
//         $images = json_decode($user->profile_image, true);
//         $firstImage = is_array($images) && count($images) > 0 ? reset($images) : null;

//         $user->profile_image = $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null;
    
//         return $user;
//     });

//     return response()->json([
//         'message' => 'Cupid matches found successfully!',
//         'status' => 200,
//         'data' => $users
//     ], 200);
// }



public function updateCupidMatch(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'message' => 'User not authenticated',
            'status' => 401
        ], 401);
    }

    $validated = $request->validate([
        'rendom' => 'required',  
        'accept' => 'nullable|boolean',  
        'decline' => 'nullable|boolean', 
    ]);

    try {

        $cupid = Cupid::where('rendom', $request->rendom)->first();

        // If cupid match is found, update it
        if ($cupid) {
            if ($request->has('accept')) {
                $cupid->accept = $request->accept;
            }

            if ($request->has('decline')) {
                $cupid->decline = $request->decline;
            }
            $cupid->save();

            $maker_rendom = User::where('id',$cupid->maker_id)->first();

            $response = [
                'message' => 'Cupid match updated successfully!',
                'status' => 200,
                'data' => [
                    'accept' => $cupid->accept,
                    'decline' => $cupid->decline,
                    'message' => $cupid->message,
                    'rendom' => $cupid->rendom,
                    'identity' => $cupid->identity,
                    'maker_rendom' => $maker_rendom->rendom,
                ]
            ];

            return response()->json($response, 200);
        } else {
            return response()->json([
                'message' => 'Cupid match not found.',
                'data' => [],
                'status' => 200
            ], 200);
        }

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error updating cupid match.',
            'error' => $e->getMessage(),
            'status' => 500
        ], 500);
    }
}



 public function matched_user(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $userLatitude = $user->latitude;
        $userLongitude = $user->longitude;

        $matchedUsers = SlideLike::where('matched_user', $user->id)
            ->where('liked_user', 1)
            ->get();

        if ($matchedUsers->isEmpty()) {
            return response()->json([
                'message' => 'No matched users found',
                'status' => 200,
                'data' => [],
            ], 200);
        }

        $matchedUserDetails = $matchedUsers->map(function ($slideLike) use ($userLatitude, $userLongitude) {
            $matchingUser = User::find($slideLike->matching_user);
            if (!$matchingUser) {
                return null;
            }

            $images = json_decode($matchingUser->profile_image, true);
            $imagePath = is_array($images) && count($images) ? reset($images) : null;

            // Calculate distance
            $matchedLatitude = $matchingUser->latitude;
            $matchedLongitude = $matchingUser->longitude;
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $matchedLatitude, $matchedLongitude);

            return [
                'id' => $matchingUser->id,
                'name' => $matchingUser->name,
                'age' => $matchingUser->age,
                'rendom' => $matchingUser->rendom,
                'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
                'distance' => round($distance) . ' km',
            ];
        })
        ->filter()
        ->unique('id')
        ->values();   

        return response()->json([
            'message' => 'Matched users found successfully',
            'status' => 200,
            'data' => $matchedUserDetails,
        ], 200);
    }

    public function handleUserInteractions(Request $request)
        {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $matchingUserId = $request->input('matching_user'); 
            $superLike = $request->input('super_like', 0);  
            $likedUser = $request->input('liked_user', 0); 
            $dislike = $request->input('dislike', 0);  
            $matchedUser = $user->id; 

            if (!$matchingUserId || !User::where('rendom',$matchingUserId)->first()) {
                return response()->json([
                    'message' => 'Matching user not found',
                    'data' => [],
                    'status'=>200
                ], 200);
            }

            $user_id = User::where('rendom',$matchingUserId)->first();

            $existingInteraction = SlideLike::where('matching_user', $user_id)
                ->where('matched_user', $user->id)
                ->first();

            if ($existingInteraction) {
                return response()->json(['message' => 'Interaction already exists'], 400);
            }

            $slideLike = new SlideLike();
            $slideLike->matching_user = $user_id->id;
            $slideLike->matched_user = $user->id;

            // Assign action values to corresponding fields
            if ($superLike) {
                $slideLike->super_like = 1;
            }
            if ($likedUser) {
                $slideLike->liked_user = 1;
            }
            if ($dislike) {
                $slideLike->dislike = 1;
            }

            // Save the SlideLike interaction
            $slideLike->save();

            return response()->json([
                'message' => 'Interaction saved successfully',
                'data' => [],
                'status' => 200,
             ], 200);
        }


        public function getUserInteractionsCount(Request $request)
        {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
    
            // Count the number of super likes, likes, and dislikes for the authenticated user
            $likedCount = SlideLike::where('matched_user', $user->id)
                ->where('liked_user', 1)
                ->count();
    
            $dislikedCount = SlideLike::where('matched_user', $user->id)
                ->where('dislike', 1)
                ->count();
    
            $superLikedCount = SlideLike::where('matched_user', $user->id)
                ->where('super_like', 1)
                ->count();
    
            return response()->json([
                'message' => 'Interaction counts fetched successfully',
                'data' => [
                    'liked_count' => $likedCount,
                    'disliked_count' => $dislikedCount,
                    'super_liked_count' => $superLikedCount,
                ],
                'status' => 200,
            ], 200);
        }


        public function getUserData(Request $request)
        {
            $request->validate([
                'rendom' => 'required', 
            ]);
        
            // Retrieve the value of 'rendom'
            $phoneNumber = $request->input('rendom');
            
            // Find the user by the 'rendom' field
            $user = User::where('rendom', $phoneNumber)->first();
            
            if(!$user){
                return response()->json([
                    'message' => 'User not found',
                    'status' => 200,
                    'data' => [],
                ]);
            }
            
            $matchingActivities = Activity::where('user_id', $user->id)
                                ->where('status', 2)
                                ->get();

$activityIds = $matchingActivities->pluck('id'); 


    $attendUsers = OtherInterest::where('user_id', $user->id)
        ->where('confirm', 6)
        ->count();

    $ghostUsers = OtherInterest::where('user_id', $user->id)
        ->where('confirm', 3)
        ->count();

    $hostedActivity = Activity::where('user_id', $user->id)
        ->count();

$interestIds = OtherInterest::whereIn('activity_id', $activityIds)->get();

$userDetailsFromInterest = $interestIds->pluck('user_id');

$userDetailsFromInterest2 = User::whereIn('id', $userDetailsFromInterest)->get();

$userList = $userDetailsFromInterest2->map(function ($user) {
    $imagePath = null;
    if ($user->profile_image) {
        $images = json_decode($user->profile_image, true); 
        if (is_array($images) && count($images)) {
            $imagePath = reset($images);
        }
    }

    $chat = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $user->id)
                ->orderBy('id','DESC')
                ->first();

    return [
        'id' => $user->id,
        'user_rendom' => $user->rendom,
        'name' => $user->name,
        'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
        'form' => 'match',
        'last_message' => $chat->message ?? null,
    ];
});

/* -------------------- 2. FRIENDS FROM LIKES (SlideLike) -------------------- */
$likeUser = SlideLike::where('matched_user', $user->id);
$likeUserDetails = $likeUser->pluck('matching_user'); 

$likeUserDetails2 = User::whereIn('id', $likeUserDetails)->get();

$likeUserList = $likeUserDetails2->map(function ($user) {
    $imagePath = null;
    if ($user->profile_image) {
        $images = json_decode($user->profile_image, true); 
        if (is_array($images) && count($images)) {
            $imagePath = reset($images);
        }
    }

    $chat = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $user->id)
                ->orderBy('id','DESC')
                ->first();

    return [
        'id' => $user->id,
        'user_rendom' => $user->rendom,
        'name' => $user->name,
        'image' => $imagePath ? asset('uploads/app/profile_images/' . $imagePath) : null,
        'form' => 'activity',
        'last_message' => $chat->message ?? null,
    ];
});

/* -------------------- 3. FRIENDS FROM CUPID MATCHES -------------------- */
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

    $chat = Chat::where('sender_id', Auth::id())
                ->where('receiver_id', $matchedUser->id)
                ->orderBy('id','DESC')
                ->first();

    return [
        'id' => $matchedUser->id,
        'user_rendom' => $matchedUser->rendom,
        'name' => $matchedUser->name,
        'image' => $firstImage ? asset('uploads/app/profile_images/' . $firstImage) : null,
        'form' => 'match',
        'last_message' => $chat->message ?? null,
    ];
})->filter();

/* -------------------- 4. CALCULATE FRIEND COUNTS -------------------- */
$friendFromInterestsCount = $userList->count();
$friendFromLikesCount = $likeUserList->count();
$friendFromCupidCount = $matchedUsers->count();

$totalFriendCount = $friendFromInterestsCount + $friendFromLikesCount + $friendFromCupidCount;

/* -------------------- 5. MERGE ALL USERS -------------------- */
$userList = collect($userList);
$likeUserList = collect($likeUserList);
$matchedUsers = collect($matchedUsers);

$matchUsers = $userList->merge($likeUserList)->merge($matchedUsers);
            // If the user is found
            if ($user) {
                $interestField = $user->interest;
                
                // Decode the 'interest' field (assuming it's stored as a JSON string)
                $interestFieldDecoded = json_decode($interestField, true);
                
                // Check if decoded data is an array
                if (!is_array($interestFieldDecoded)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid interest data',
                    ], 400);
                }
        
                // Flatten the array and split items by commas
                $interestIds = [];
                foreach ($interestFieldDecoded as $item) {
                    $interestIds = array_merge($interestIds, explode(',', $item));
                }
        
                // Clean up the array by trimming any extra spaces
                $interestIds = array_map('trim', $interestIds);
                
                // Fetch the interests from the Interest model based on IDs
                $interests = Interest::whereIn('id', $interestIds)->get(['name', 'icon']);
        
                // Decode the profile images field
                $profileImages = json_decode($user->profile_image, true);
                $imageUrls = [];
                foreach ($profileImages as $image) {
                    $imageUrls[] = asset('uploads/app/profile_images/' . $image);
                }
        
                // Prepare user data
                $userData = [
                    'number' => $user->number,
                    'name' => $user->name,
                    'about' => $user->about,
                    'email' => $user->email,
                    'age' => $user->age,
                    'gender' => $user->gender,
                    'looking_for' => $user->looking_for,
                    'interest' => $interests,
                    'status' => $user->status,
                    'profile_images' => $imageUrls,
                    'attendUsers' => $attendUsers,
                    'ghostUsers' => $ghostUsers,
                    'hostedActivity' => $hostedActivity,
                    'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
                ];
        
                // Return the user data as a JSON response
                return response()->json([
                    'message' => 'Data fetched successfully!',
                    'status' => 200,
                    'data' => $userData,
                ]);
            } else {
                // If no user is found, return an error response
                return response()->json([
                    'message' => 'User not found',
                    'status' => 200,
                    'data' => [],
                ]);
            }
        }
        



}

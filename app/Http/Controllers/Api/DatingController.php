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
                'match_percentage' => number_format($matchingPercentage, 2),
                'message' => $message ? $message->message : null,
                'message_status' => $message ? $message->status : null,
                'distance' => $distance . ' km', // Always include the distance
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
                // You can add any other necessary fields you want to return in the response
            ]
        ];

        return response()->json($response, 200);

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

}

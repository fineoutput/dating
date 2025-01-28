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
        $message = "Interest fetched successfully"; 
        $status = 200;
        $statusCode = 200; 
        $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);


        return response()->json([
            'message' => $message,
            'status' => $status,
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
            ], 400);
        }


        try {
            $otherInterest = OtherInterest::create([
                'user_id' => $user->id,
                'activity_id' => $request->activity_id,
                'confirm' => 0,
            ]);

            return response()->json([
                'message' => 'Interest added successfully',
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
    
        $interests = OtherInterest::where('activity_id', $request->activity_id)
                                   ->where('confirm', 0)
                                   ->get();
    
        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No interests found for this activity.',
            ], 404);
        }

        $interestsArray = $interests->map(function ($interest) {
            return [
                'id' => $interest->id,
                'user' => $interest->user->name,
                'user_id' => $interest->user_id,
                'activity_id' => $interest->activity_id,
                'confirm' => $interest->confirm,
            ];
        });
    
        return response()->json([
            'message' => 'User interests fetched successfully',
            'data' => $interestsArray,
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
    
        // Fetch the interests with confirm status set to 1
        $interests = OtherInterest::where('activity_id', $request->activity_id)
                                   ->where('confirm', 1)
                                   ->get();
    
        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No confirmed interests found for this activity.',
                'data' => [],
            ], 404); // Status code 404 if no interests found
        }
    
        // Transform the collection to an array
        $interestsArray = $interests->map(function ($interest) {
            return [
                'id' => $interest->id,
                'user' => $interest->user->name ?? '',
                'user_id' => $interest->user_id,
                'activity_id' => $interest->activity_id,
                'confirm' => $interest->confirm,
            ];
        });
    
        // Return the response with status code 200 if interests found
        return response()->json([
            'message' => 'Confirmed user interests fetched successfully',
            'status' => 200,
            'data' => $interestsArray,
        ], 200); 
    }

    
    public function confirm_user_interest(Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    $user = Auth::user();

    $request->validate([
        'activity_id' => 'required',
        'confirm' => 'required|boolean', 
    ]);
    $interest = OtherInterest::where('activity_id', $request->activity_id) 
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

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
        $status = "success";
        $statusCode = 200; 

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
        $status = "success";
        $statusCode = 200; 

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
        $status = "success";
        $statusCode = 200; 

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

        $interests = OtherInterest::where('activity_id', $request->activity_id)->where('confirm',0)->get();

        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No interests found for this activity.',
            ], 404);
        }

        return response()->json([
            'message' => 'User interests fetched successfully',
            'data' => $interests,
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

        $interests = OtherInterest::where('activity_id', $request->activity_id)->where('confirm',1)->get();

        if ($interests->isEmpty()) {
            return response()->json([
                'message' => 'No interests found for this activity.',
            ], 404);
        }

        return response()->json([
            'message' => 'User interests fetched successfully',
            'data' => $interests,
        ]);
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
            'user_id' => $interest->user_id,
            'confirm' => $interest->confirm,
        ],
    ]);
}


}

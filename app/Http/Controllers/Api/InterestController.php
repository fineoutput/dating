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

}

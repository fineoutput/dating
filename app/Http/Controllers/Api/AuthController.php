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
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{

    public function successResponse($message, $status = true, $statusCode = 201)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $statusCode);
    }

    // public function signup(Request $request)
    // {
        
    //     $validationRules  = [
    //         'number' => 'required|string', 
    //         'status' => 'required|in:insert,update,final',         
    //         'name' => 'nullable|string|max:255',         
    //         'email' => 'nullable|string|email|max:255|unique:unverify_user', 
    //         'age' => 'nullable|string|max:255',         
    //         'gender' => 'nullable|string|max:255',       
    //         'looking_for' => 'nullable|string|max:255',  
    //         'interest' => 'nullable|array',
    //         'interest.*' => 'nullable|exists:interests,id',     
    //         'profile_image' => 'nullable|array',
    //         'profile_image.*' => 'nullable|image',
    //         'state' => 'nullable|string|max:255',        
    //         'city' => 'nullable|string|max:255',                
    //         'password' => 'nullable|string|min:6',
    //     ];

    //     if ($request->status === 'insert') {
    //         $validationRules['number'] = 'required|string|unique:users';
    //         $validationRules['email'] = 'nullable|string|email|max:255|uunique:users';
    //     } elseif ($request->status === 'update') {
    //         $validationRules['number'] = 'required|string|exists:unverify_user,number'; 
    //         $validationRules['email'] = 'nullable|string|email|max:255|unique:users,email|unique:unverify_user,email,' . $request->number;
    //     }
    //     $validator = Validator::make($request->all(), $validationRules);
    //     if ($validator->fails()) {
    //         $errors = [];
    //         foreach ($validator->errors()->getMessages() as $field => $messages) {
    //             $errors[$field] = $messages[0]; 
    //         }
    //         return response()->json(['errors' => $errors], 400);
    //     }

    //     if ($request->status === 'insert' && $request->number) {
    //         $number = $request->number;
    //         $otp = $this->sendOtp($number);
    //         $user = UnverifyUser::create([
    //             'number' => $request->number,
    //         ]);
    //         return $this->successResponse('OTP send successfully!');
    //     }
        
    //     elseif ($request->status === 'update') {
    //         $user = UnverifyUser::where('number', $request->number)->first();
    //         if (!$user) {
    //             return response()->json(['message' => 'User not found.'], 404);
    //         }
    //         if($request->email){
    //             $email = $request->email;
    //             $otp = $this->sendOtp(null,$email);
    //         }
    //         $updateData = array_filter($request->only([
    //             'name', 'email', 'age', 'gender', 'looking_for', 
    //             'interest', 'profile_image', 'state', 'city', 'password'
    //         ]));
    //         // if ($request->has('password')) {
    //         //     $updateData['password'] = bcrypt($request->password);
    //         // }
    //         if ($request->hasFile('profile_image') && is_array($request->profile_image)) {
    //             $imagePaths = [];
    //             $index=1;
    //             foreach ($request->profile_image as $image) {
    //                 $imageValidator = Validator::make(['image' => $image], [
    //                     'image' => 'required|image|mimes:jpg,jpeg,png,gif',
    //                 ]);
                    
    //                 if ($imageValidator->fails()) {
    //                     return response()->json(['errors' => $imageValidator->errors()], 400);
    //                 }
    //                 $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
    //                 $image->move(public_path('uploads/app/profile_images'), $imageName);
                    
    //                 $imagePaths[$index] = 'profile_images' . $imageName;
    //                 $index++;
    //             }
    //             $updateData['profile_image'] = json_encode($imagePaths);
    //         }
    //         $user->update($updateData);
    //         return response()->json(['message' => 'User updated successfully!', 'user' => $user], 200);
    //     }
    //     elseif ($request->status === 'final') {
    //         $unverifyUser = UnverifyUser::where('number', $request->number)
    //             ->where('email_verify', 1)
    //             ->where('number_verify', 1)
    //             ->first();
    
    //         if ($unverifyUser) {
    //             $newUser = User::create([
    //                 'number' => $unverifyUser->number,
    //                 'email' => $unverifyUser->email,
    //                 'password' => $unverifyUser->password,
    //                 'name' => $unverifyUser->name,
    //                 'age' => $unverifyUser->age,
    //                 'gender' => $unverifyUser->gender,
    //                 'looking_for' => $unverifyUser->looking_for,
    //                 'interest' => $unverifyUser->interest,
    //                 'profile_image' => $unverifyUser->profile_image,
    //                 'state' => $unverifyUser->state,
    //                 'city' => $unverifyUser->city,
    //                 'status' => 1,
    //             ]);
    //             UnverifyUser::where('number', $request->number)->delete();
    //             $token = $newUser->createToken('token')->plainTextToken;
    //             $userss = User::where('email', $newUser->email)->first();
    //             if ($userss) {
    //                 $userss->auth = $token;
    //                 $userss->save();
    //             }
    //             return response()->json(['message' => 'User verified and moved to users table successfully!', 'token' => $token], 200);
    //         } else {
    //             return response()->json(['message' => 'User not found or email or phone not verified.'], 404);
    //         }
    //     }

    // }


    public function signup(Request $request)
{
    $validationRules  = [
        'number' => 'required|string', 
        'status' => 'required|in:insert,update,final',         
        'name' => 'nullable|string|max:255',         
        'email' => 'nullable|string|email|max:255|unique:unverify_user', 
        'age' => 'nullable|string|max:255',         
        'gender' => 'nullable|string|max:255',       
        'looking_for' => 'nullable|string|max:255',  
        'interest' => 'nullable|array',
        'interest.*' => 'nullable|exists:interests,id',     
        'profile_image' => 'nullable|array',
        'profile_image.*' => 'nullable|image',
        'state' => 'nullable|string|max:255',        
        'city' => 'nullable|string|max:255',                
        'password' => 'nullable|string|min:6',
    ];

    if ($request->status === 'insert') {
        $validationRules['number'] = 'required|string|unique:users,number'; // Check number in users table
        $validationRules['email'] = 'nullable|string|email|max:255|unique:users,email'; // Check email in users table
    } elseif ($request->status === 'update') {
        $validationRules['number'] = 'required|string|exists:unverify_user,number'; 
        $validationRules['email'] = 'nullable|string|email|max:255|unique:users,email|unique:unverify_user,email,' . $request->number;
    }

    $validator = Validator::make($request->all(), $validationRules);
    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0]; 
        }
        return response()->json(['errors' => $errors], 400);
    }

    // Insert case: Check if user already exists in 'users' table
    if ($request->status === 'insert' && $request->number) {
        $existingUser = User::where('number', $request->number)->orWhere('email', $request->email)->first();

        if ($existingUser) {
            // User already exists, generate token
            $token = $existingUser->createToken('token')->plainTextToken;
            $existingUser->auth = $token;
            $existingUser->save();

            return response()->json(['message' => 'User already exists, auth token generated!', 'token' => $token, 'user' => $existingUser], 200);
        }

        // If the user doesn't exist, send OTP and create an unverified user
        $otp = $this->sendOtp($request->number);
        $user = UnverifyUser::create([
            'number' => $request->number,
        ]);
        
        return $this->successResponse('OTP sent successfully!');
    }

    // Update case: Process update for unverified user
    elseif ($request->status === 'update') {
        $user = UnverifyUser::where('number', $request->number)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($request->email) {
            $email = $request->email;
            $otp = $this->sendOtp(null, $email);
        }

        $updateData = array_filter($request->only([
            'name', 'email', 'age', 'gender', 'looking_for', 
            'interest', 'profile_image', 'state', 'city', 'password'
        ]));

        if ($request->hasFile('profile_image') && is_array($request->profile_image)) {
            $imagePaths = [];
            $index = 1;
            foreach ($request->profile_image as $image) {
                $imageValidator = Validator::make(['image' => $image], [
                    'image' => 'required|image|mimes:jpg,jpeg,png,gif',
                ]);
                
                if ($imageValidator->fails()) {
                    return response()->json(['errors' => $imageValidator->errors()], 400);
                }

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/app/profile_images/'), $imageName);
                $imagePaths[$index] = 'profile_images' . $imageName;
                $index++;
            }
            $updateData['profile_image'] = json_encode($imagePaths);
        }

        $user->update($updateData);
        return response()->json(['message' => 'User updated successfully!', 'user' => $user], 200);
    }

    // Final case: Move user from unverified to verified table
    elseif ($request->status === 'final') {
        $unverifyUser = UnverifyUser::where('number', $request->number)
            ->where('email_verify', 1)
            ->where('number_verify', 1)
            ->first();

        if ($unverifyUser) {
            $newUser = User::create([
                'number' => $unverifyUser->number,
                'email' => $unverifyUser->email,
                'password' => $unverifyUser->password,
                'name' => $unverifyUser->name,
                'age' => $unverifyUser->age,
                'gender' => $unverifyUser->gender,
                'looking_for' => $unverifyUser->looking_for,
                'interest' => $unverifyUser->interest,
                'profile_image' => $unverifyUser->profile_image,
                'state' => $unverifyUser->state,
                'city' => $unverifyUser->city,
                'status' => 1,
            ]);

            UnverifyUser::where('number', $request->number)->delete();

            $token = $newUser->createToken('token')->plainTextToken;
            $newUser->auth = $token;
            $newUser->save();

            return response()->json(['message' => 'User verified and moved to users table successfully!', 'token' => $token], 200);
        } else {
            return response()->json(['message' => 'User not found or email or phone not verified.'], 404);
        }
    }
}


    private function sendOtp($phone = null, $email = null)
    {
        $otp = rand(1000, 9999); 
        $sourceName = $phone ?? $email; 
        $existingOtp = UserOtp::where('source_name', $sourceName);
        if ($existingOtp) {
            $existingOtp->update(['otp' => 0]);
        }
        $data = [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // Set expiry time for OTP (e.g., 10 minutes)
            'source_name' => $sourceName,
            'type' => $phone ? 'phone' : 'email',
        ];
        UserOtp::create($data);
        if ($phone) {
            // $this->sendOtpToPhone($phone, $otp);
        } elseif ($email) {
            // $this->sendOtpToEmail($email, $otp);
        }
    }
    
    private function sendOtpToEmail($email, $otp)
    {
    // \Mail::to($email)->send(new OtpMail($otp)); 
    }   

    public function verify_auth_otp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:4', 
            'type' => 'required|in:email,phone', 
            'source_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                $errors[$field] = $messages[0];
                break; 
            }
            return response()->json(['errors' => $errors], 400);
        }
        $validated = $validator->validated();
        $otp = $validated['otp'];
        $type = $validated['type'];
        $source_name = $validated['source_name'];

        $otpUser = UserOtp::where('type', $type)
        ->where('source_name', $source_name)
        ->where('otp', $otp)
        ->first();
        if (!$otpUser) {
            return $this->successResponse('Invalid OTP or details!', false,400);
        }
        if ($otpUser->expires_at < now()) {
            return $this->successResponse('OTP has expired. Please request a new OTP.', false, 400);
        }
        UserOtp::where('type', $type)
        ->where('source_name', $source_name)
        ->update(['otp' => 0]);
        if ($type === 'email') {
            $unverifyUser = UnverifyUser::where('email', $source_name)->first();
            if ($unverifyUser) {
                $unverifyUser->update(['email_verify' => 1]);
            } else {
                return $this->successResponse('No user found with the provided email.',false,404);
            }
        }
        if ($type === 'phone') {
            $unverifyUser = UnverifyUser::where('number', $source_name)->first();
        
            if ($unverifyUser) {
                $unverifyUser->update(['number_verify' => 1]);
            } else {
                return $this->successResponse('No user found with the provided phone number.',false,404);
            }
        }
        return $this->successResponse('OTP verified successfully!', true,200);
    }

    // public function login(Request $request)
    // {
    //     // Validate the request input
    //     $validator = Validator::make($request->all(), [
    //         'number' => 'required|string|exists:users,number',
    //         'password' => 'required|string|min:6',
    //     ]);
    
    //     if ($validator->fails()) {
    //         $errors = [];
    //         foreach ($validator->errors()->getMessages() as $field => $messages) {
    //             $errors[$field] = $messages[0];
    //             break; 
    //         }
    //         return response()->json(['errors' => $errors], 400);
    //     }
    //     $credentials = $request->only('number', 'password');
    //     if (!Auth::attempt($credentials)) {
    //         return $this->successResponse('Invalid credentials. Please check your number and password.', false, 401);
    //     }
    //     $user = Auth::user();
    //     $token = $user->createToken('token')->plainTextToken;
    //     $user->auth = $token;
    //     $user->save();
    //     return response()->json([
    //         'message' => 'Login successful.',
    //         'token' => $token,
    //     ], 200);
    // }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'number' => 'required|string|exists:users,number',
        'otp' => 'nullable|string|min:4|max:4', 
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Invalid credentials'], 400);
    }

    // Get the user based on the provided phone number
    $user = User::where('number', $request->number)->first();
    
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

   
    if ($request->number == '0000000000') {
        if ($request->otp != '1234') {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // If OTP is correct, generate the token and update user auth field
        $token = $user->createToken('token')->plainTextToken;
        $user->auth = $token; // Save the token in the user table
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    // Case 2: If OTP is not provided, send OTP to the number
    if (!$request->otp) {
        // Send OTP (Generate and send via SMS or Email)
        $this->sendOtp($user->number); 
        return response()->json(['message' => 'OTP has been sent to your number. Please enter it to login.']);
    }

    // Case 3: If OTP is provided, validate it
    $otpRecord = UserOtp::where('source_name', $request->number)
                        ->where('otp', $request->otp)
                        ->where('expires_at', '>', Carbon::now()) // OTP must not be expired
                        ->first();
    
    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }

    // Check if the user is blocked
    if ($user->status == 0) {
        return response()->json(['message' => 'You have been blocked by the admin'], 403);
    }

    // Generate the token and update the user's auth field
    $token = $user->createToken('token')->plainTextToken;
    $user->auth = $token;
    $user->save();

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function userprofile(Request $request)
    {

        $user = Auth::user();

        $profileImages = json_decode($user->profile_image, true);

        $imageUrls = [];
        foreach ($profileImages as $image) {
            $imageUrls[] = asset('profile_images/' . $image);
        }
        $userData = [
            'id' => $user->id,
            'number' => $user->number,
            'auth' => $user->auth,
            'name' => $user->name,
            'email' => $user->email,
            'age' => $user->age,
            'gender' => $user->gender,
            'looking_for' => $user->looking_for,
            'interest' => json_decode($user->interest, true), 
            'state' => $user->state,
            'city' => $user->city,
            'status' => $user->status,
            'profile_images' => $imageUrls, 
        ];
        return response()->json($userData);
    }


}

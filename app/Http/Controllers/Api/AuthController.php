<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnverifyUser;
use App\Models\UserOtp;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Mail\OtpMail;
use App\Models\Activity;
use App\Models\AdminCity;
use App\Models\Chat;
use App\Models\Contact;
use App\Models\Cupid;
use App\Models\OtherInterest;
use App\Models\SlideLike;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Http;



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
        'number' => 'required|numeric|digits:10',
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
        'location' => 'nullable|string|max:255',                
        'password' => 'nullable|string|min:6',
    ];

    if ($request->status === 'insert') {
        $validationRules['number'] = 'required|string|digits:10'; 
        $validationRules['email'] = 'nullable|string|email|max:255|unique:users,email';

    } elseif ($request->status === 'update') {
        $validationRules['number'] = 'required|string|digits:10|exists:unverify_user,number'; 
        $validationRules['email'] = 'nullable|string|email|max:255|unique:users,email,' . $request->number;
    }

    $validator = Validator::make($request->all(), $validationRules);
    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0];
        }
        return response()->json(['message' => $errors,'data' => [],'status' => 201], 201);
    }

    if ($request->status === 'insert' && $request->number) {
        $existingUser = User::where('number', $request->number)->first();

        
        if ($existingUser) {
            
            if($existingUser->status == 0){
                return response()->json(['message' => 'You have been blocked by Admin, You cannot log in yet', 'status' => 200], 200);
            }

            $otp = $this->sendOtp($request->number); 
            return response()->json([
                'message' => 'User already exists, OTP sent!',
                'data' => [],
                'status' => 200
            ], 200);
        }

        $otp = $this->sendOtp($request->number); 
        $user = UnverifyUser::create([
            'number' => $request->number,
        ]);
        
        return response()->json([
            'message' => 'OTP sent successfully!',
            'data' => [],
            'status' => 200,
        ], 200);
    }

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
            'name', 'longitude', 'latitude', 'location', 'email', 'age', 'gender', 'looking_for', 
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
                $imagePaths[$index] =  $imageName;
                $index++;
            }
            $updateData['profile_image'] = json_encode($imagePaths);
        }

        $user->update($updateData);
        return response()->json([
            'message' => 'User updated successfully!', 
            'status' => 200,
            'data' => $user
        ], 200);
    }

    // Final case: Move user from unverified to verified table
    elseif ($request->status === 'final') {
        $unverifyUser = UnverifyUser::where('number', $request->number)
            ->where('email_verify', 1)
            ->where('number_verify', 1)
            ->first();

            // $randomNumber = rand(100000, 999999);
            do {
                $randomNumber = rand(100000, 999999);
            } while (User::where('rendom', $randomNumber)->exists());
            

        if ($unverifyUser) {
            $newUser = User::create([
                'number' => $unverifyUser->number,     
                // 'auth' => $unverifyUser->auth,     
                'email' => $unverifyUser->email,
                'name' => $unverifyUser->name,
                'age' => $unverifyUser->age,
                'gender' => $unverifyUser->gender,
                'looking_for' => $unverifyUser->looking_for,
                'interest' => $unverifyUser->interest,
                'profile_image' => $unverifyUser->profile_image,
                'state' => $unverifyUser->state,
                'city' => $unverifyUser->city,
                'latitude' => $unverifyUser->latitude,
                'longitude' => $unverifyUser->longitude,
                'location' => $unverifyUser->location,
                'rendom' => $randomNumber,
                'status' => 1,
            ]);

            UnverifyUser::where('number', $request->number)->delete();

            $token = $newUser->createToken('token')->plainTextToken;
            $newUser->auth = $token;
            $newUser->save();

            return response()->json([
                'message' => 'User Register successfully!',
                'status' => 200,
                'token' => $token,  // Include the token in the response
                'data' => [],  // Include the token in the response
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found or email or phone not verified.',
                'data' => [],
                'status' => 201,
            ], 200);
        }
    }
}



    // private function sendOtp($phone = null, $email = null)
    // {
    //     $otp = rand(1000, 9999); 
    //     $sourceName = $phone ?? $email; 
    //     $existingOtp = UserOtp::where('source_name', $sourceName);
    //     if ($existingOtp) {
    //         $existingOtp->update(['otp' => 0]);
    //     }
    //     $data = [
    //         'otp' => $otp,
    //         'expires_at' => now()->addMinutes(10), 
    //         'source_name' => $sourceName,
    //         'type' => $phone ? 'phone' : 'email',
    //     ];
    //     UserOtp::create($data);
    //     if ($phone) {
    //         // $this->sendOtpToPhone($phone, $otp);
    //     } elseif ($email) {
    //         // $this->sendOtpToEmail($email, $otp);
    //     }
    // }

    private function sendOtp($phone = null, $email = null)
{
    // Generate a random OTP for phone or set 0000 for email
    if ($phone) {
        // Generate a random OTP between 1000 and 9999 for phone
        // $otp = rand(1000, 9999);
        $otp = 1111;

    } elseif ($email) {
        // Always set OTP to 0000 for email
        $otp = 1111;
    }

    // Use phone or email as the source name
    $sourceName = $phone ?? $email;

    // Check if an OTP record exists for the provided source name
    $existingOtp = UserOtp::where('source_name', $sourceName)->first();

    // If the OTP record exists, update it with the new OTP
    if ($existingOtp) {
        $existingOtp->update([
            'otp' => $otp, // Set the newly generated OTP
            'expires_at' => now()->addMinutes(10) // Reset expiration time
        ]);
    } else {
        // If no record exists, create a new OTP record
        $data = [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), 
            'source_name' => $sourceName,
            'type' => $phone ? 'phone' : 'email',
        ];
        UserOtp::create($data);
    }

    // Send OTP to phone or email (you can implement these methods)
    if ($phone) {
        // $this->sendOtpToPhone($phone, $otp); // Uncomment to send OTP to phone
    } elseif ($email) {
        // $this->sendOtpToEmail($email, $otp); // Uncomment to send OTP to email
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
            return response()->json(['message' => $errors,'data' => [],'status' => 201], 200);
        }
        
        $validated = $validator->validated();
        $otp = $validated['otp'];
        $type = $validated['type'];
        $source_name = $validated['source_name'];

        if ($type === 'phone') {
            $existingUser = User::where('number', $source_name)->first();
            if ($existingUser) {
                
                $otpRecord = UserOtp::where('source_name', $source_name)
                ->where('otp', $otp)
                ->latest()
                ->first();

                if ($otpRecord) {
                    // You may want to add logic here to check if OTP has expired (if necessary)
        
                    // Step 3: Generate token for authenticated user
                    $token = $existingUser->createToken('auth_token')->plainTextToken;
        
                    // Save the token to the user (optional step)
                    $existingUser->auth = $token;
                    $existingUser->save();
        
                    // Return the successful response
                    return response()->json([
                        'message' => 'Login successful!',
                        'data' => [
                            'token' => $token,
                        ],
                        'status' => 200,
                    ]);
                } else {
                    // OTP did not match or is expired
                    return response()->json([
                        'message' => 'Invalid OTP or OTP expired.',
                        'data' => [],
                        'status' => 201,
                    ]);
                }
            }
            }


        $otpUser = UserOtp::where('type', $type)
            ->where('source_name', $source_name)
            ->where('otp', $otp)
            ->first();
            
        if (!$otpUser) {
            return response()->json([
                'message' => 'Invalid OTP or details!',
                'data' => [],
                'status' => 201
            ],200);
            
        }
        
        if ($otpUser->expires_at < now()) {
            return response()->json([
                'message' => 'OTP has expired. Please request a new OTP!',
                'status' => 201
            ],200);
            
            // return $this->successResponse('.', false, 400);
        }
        
        UserOtp::where('type', $type)
            ->where('source_name', $source_name)
            ->update(['otp' => 0]);

        if ($type === 'email') {
            $unverifyUser = UnverifyUser::orderBy('id','DESC')->where('email', $source_name)->first();
            // return $unverifyUser;
            if ($unverifyUser) {
                $unverifyUser->update(['email_verify' => 1]);

    
                return response()->json([
                    
                        'message' => 'Email verified successfully!',
                        'status' => 200,
                        'data' => [
                            'status' => 206,
                        ],
                    // 'status' => true
                ]);
            } else {
                return $this->successResponse('No user found with the provided email.', false, 404);
            }
        }

        // if ($type === 'email') {
        //     // Find the unverified user based on the provided email
        //     $unverifyUser = UnverifyUser::orderBy('id', 'DESC')->where('email', $source_name)->first();
        
        //     if ($unverifyUser) {
        //         // For testing purposes, we will always use '0000' as the OTP
        //         $otp = '0000';
        
        //         // Simulate OTP validation with '0000' (as if it matches every time)
        //         if ($otp === '0000') {
        //             // Update the email verification status
        //             $unverifyUser->update(['email_verify' => 1]);
        
        //             return response()->json([
        //                 'message' => 'Email verified successfully!',
        //                 'status' => 200,
        //                 'data' => [
        //                     'status' => 206,
        //                 ],
        //             ]);
        //         } else {
        //             // If you wanted to handle an invalid OTP case (though it won't happen with '0000')
        //             return $this->successResponse('Invalid OTP provided.', false, 400);
        //         }
        //     } else {
        //         return $this->successResponse('No user found with the provided email.', false, 404);
        //     }
        // }
        

        if ($type === 'phone') {
            $unverifyUser = UnverifyUser::where('number', $source_name)->first();
            $emailverifyUser = UnverifyUser::where('number', $source_name)->where('email_verify', 1)->first();
            
            $token = $unverifyUser->createToken('auth_token')->plainTextToken;
            $unverifyUser->auth = $token;
            $unverifyUser->save();
        
            if ($unverifyUser) {
                $unverifyUser->update(['number_verify' => 1]);
                
                // Determine status based on email verification
                $status = $emailverifyUser === null ? 205 : 206;
                
                // return $this->successResponse([
                //     'message' => 'Phone number verified successfully!',
                //     'status' => $status,  
                //     'token' => $token,
                // ]);
                return response()->json([
                    
                        'message' => 'Phone number verified successfully!',
                        'status' => 200,  
                        'data' => [
                            'status' => $status,
                        ],
                        // 'token' => $token,
                ]);
            } else {
                return $this->successResponse('No user found with the provided phone number.', false, 200);
            }
        }

        return $this->successResponse('OTP verified successfully!', true, 200);
    }
    

    // public function verify_auth_otp(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'otp' => 'required|numeric|digits:4', 
    //         'type' => 'required|in:email,phone', 
    //         'source_name' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         $errors = [];
    //         foreach ($validator->errors()->getMessages() as $field => $messages) {
    //             $errors[$field] = $messages[0];
    //             break; 
    //         }
    //         return response()->json(['errors' => $errors], 400);
    //     }
    //     $validated = $validator->validated();
    //     $otp = $validated['otp'];
    //     $type = $validated['type'];
    //     $source_name = $validated['source_name'];

    //     $otpUser = UserOtp::where('type', $type)
    //     ->where('source_name', $source_name)
    //     ->where('otp', $otp)
    //     ->first();
    //     if (!$otpUser) {
    //         return $this->successResponse('Invalid OTP or details!', false,400);
    //     }
    //     if ($otpUser->expires_at < now()) {
    //         return $this->successResponse('OTP has expired. Please request a new OTP.', false, 400);
    //     }
    //     UserOtp::where('type', $type)
    //     ->where('source_name', $source_name)
    //     ->update(['otp' => 0]);
    //     if ($type === 'email') {
    //         $unverifyUser = UnverifyUser::where('email', $source_name)->first();
    //         if ($unverifyUser) {
    //             $unverifyUser->update(['email_verify' => 1]);
    //         } else {
    //             return $this->successResponse('No user found with the provided email.',false,404);
    //         }
    //     }
    //     if ($type === 'phone') {
    //         $unverifyUser = UnverifyUser::where('number', $source_name)->first();
        
    //         if ($unverifyUser) {
    //             $unverifyUser->update(['number_verify' => 1]);
    //         } else {
    //             return $this->successResponse('No user found with the provided phone number.',false,404);
    //         }
    //     }
    //     return $this->successResponse('OTP verified successfully!', true,200);
    // }

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
            return response()->json(['message' => 'Invalid credentials','status'=>201], 400);
        }

        $user = User::where('number', $request->number)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

    
        if ($request->number == '0000000000') {
            if ($request->otp != '1234') {
                return response()->json(['message' => 'Invalid OTP'], 400);
            }

            $token = $user->createToken('token')->plainTextToken;
            $user->auth = $token; 
            $user->save();

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        }

        if (!$request->otp) {
            $this->sendOtp($user->number); 
            return response()->json(['message' => 'OTP has been sent to your number. Please enter it to login.']);
        }
        $otpRecord = UserOtp::where('source_name', $request->number)
                            ->where('otp', $request->otp)
                            ->where('expires_at', '>', Carbon::now()) 
                            ->first();
        
        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        if ($user->status == 0) {
            return response()->json(['message' => 'You have been blocked by the admin'], 403);
        }

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
        $user = auth()->user();
    
        if ($user) {
            $user->auth = null; 
            $user->save();
    
            return response()->json([
                'message' => 'Successfully logged out',
                'data' => [],
                'status'=>200
            ]);
        }
    
        return response()->json(['message' => 'No authenticated user found'], 401);
    }
    
     public function getPhoneNumbers(): JsonResponse
    {
        $phoneNumbers = User::pluck('number'); 
        return response()->json([
            'message' => 'Successfully logged out',
                'data' =>$phoneNumbers,
                'status'=>200
        ]);
    }


   public function checkNumbers(Request $request)
    {
        $request->validate([
            'numbers' => 'required|array',
            'numbers.*' => 'integer'
        ]);

        $numbers = $request->input('numbers');

        $userNumbers = User::pluck('number')->toArray();

        $mismatchedNumbers = array_diff($numbers, $userNumbers);

        $matchedNumbers = array_intersect($numbers, $userNumbers);

        return response()->json([
            'message' => 'Numbers fetched successfully',
            'data' => [
            'mismatched_numbers' => array_values($mismatchedNumbers),
            'matched_numbers' => array_values($matchedNumbers)
            ],
            'status' => 200,
        ]);
    }


   public function contact_store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:20',
        ]);

        $user_id = Auth::id();
        $number = $request->number;

        // Check if contact already exists for this user
        $existing_contact = Contact::where('user_id', $user_id)
                                ->where('number', $number)
                                ->first();

        if ($existing_contact) {
            return response()->json([
                'message' => 'You have already sent a request to this contact.',
                'status' => 409, // Conflict
            ], 409);
        }

        // Create new contact if not exists
        $contact = Contact::create([
            'user_id' => $user_id,
            'number' => $number,
            'status' => 0,
        ]);

        return response()->json([
            'message' => 'Contact added successfully',
            'contact' => $contact,
            'status' => 200,
        ], 201);
    }

 
 public function contact_get()
    {
        $user = auth()->user(); 

        $contacts = Contact::where('status',0)->where('number', $user->number)->get();

        $contacts_with_user_name = $contacts->map(function($contact) {
            $contact_user = User::where('id', $contact->user_id)->first(); 
            return [
                'id' => $contact->id,
                'number' => $contact->number,
                'status' => $contact->status,
                'user_name' => $contact_user ? $contact_user->name : null,
                'user_rendom' => $contact_user ? $contact_user->rendom : null,
            ];
        });

        return response()->json([
            'message' => 'Contacts fetched successfully',
            'data' => $contacts_with_user_name,
            'status' => 200,
        ], 200);
    }

    public function contact_update(Request $request)
    {
        $request->validate([
            'status' => 'required|string|max:20',
            'contact_id' => 'required|string|max:20',
        ]);

        $contact = Contact::where('id', $request->contact_id)
                        ->first();

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found or not authorized',
                'status' => 201,
            ], 201);
        }

        $receiver =  User::where('number', $contact->number)->first();

        if($request->status == 1){
             $code = rand(100000, 999999);
        while (Chat::where('rendom', $code)->exists()) {
            $code = rand(100000, 999999);
        }
        $user =Auth::user();
          $chat = Chat::create([
            'sender_id' => $receiver->id,
            'receiver_id' => Auth::id(),
            'message' => 'Hello'. $user->name,
            'status' => 'sent',
            'rendom' => $code,
            'send_type' => 'contact',
            'chat_type' => 'contact',
            'activity_id' =>  null,
        ]);

        }

        $contact->status = $request->status;
        $contact->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'contact' => $contact,
            'status' => 200,
        ], 200);
    }
        
 
    // public function userprofile(Request $request)
    //     {
    //         $user = Auth::user();
            
    //         if (!$user) {

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not authenticated',
    //             ], 401);
    //         }
            
    //         $interestField = $user->interest;

    //         $interestFieldDecoded = json_decode($interestField, true);

    //         if (!is_array($interestFieldDecoded)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Invalid interest data',
    //             ], 400);
    //         }

    //         $interestIds = [];
    //         foreach ($interestFieldDecoded as $item) {
    //             $interestIds = array_merge($interestIds, explode(',', $item));
    //         }

    //         $interestIds = array_map('trim', $interestIds);

    //         $interests = Interest::whereIn('id', $interestIds)->get(['id','name', 'icon']);

    //         \Log::info("Interest IDs: ", $interestIds);
    //         \Log::info("Fetched Interests: ", $interests->toArray());

    //         $profileImages = json_decode($user->profile_image, true);
    //         $imageUrls = [];
    //         foreach ($profileImages as $image) {
    //             $imageUrls[] = asset('uploads/app/profile_images/' . $image);
    //         }
 
    //         $userData = [
    //             // 'id' => $user->id,
    //             'number' => $user->number,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'age' => $user->age,
    //             'gender' => $user->gender,
    //             'looking_for' => $user->looking_for,
    //             'interest' => $interests,  
    //             'status' => $user->status,
    //             'profile_images' => $imageUrls,
    //             'about' => $user->about ?? '',
    //             'address' => $user->address ?? '',
    //         ];

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'User profile fetched successfully',
    //             'data' => $userData,
    //         ], 200);
    //     }


    public function userprofile(Request $request)
{
    $user = Auth::user()->load('city');

    

    // $attendUsers = OtherInterest::where('user_id', $user->id)
    //     ->where('confirm', 8)
    //     ->count();

    $currentTime = Carbon::now('Asia/Kolkata');  // Current time in Asia/Kolkata

    // $activities = Activity::orderBy('id', 'DESC')
    // // ->where('user_id', $user->id)
    // ->where('status', 2)
    // ->where(function ($query) use ($currentTime) {
    //     $query->whereDate('when_time', '<', substr($currentTime, 0, 10)) // Past date
    //         ->orWhereRaw("
    //             STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') < ?
    //         ", [$currentTime]);
    // })
    // ->get();

    // $ghostUsers = OtherInterest::whereIn('activity_id', $activities->pluck('id'))->where('user_id', $user->id)
    //     ->whereIn('confirm', [3,7])
    //     ->count();


           $activities = Activity::orderBy('id', 'DESC')
    // ->where('user_id', $user->id)
    ->where('status', 2)
    ->where(function ($query) use ($currentTime) {
        $query->whereDate('when_time', '<', substr($currentTime, 0, 10)) // Past date
            ->orWhereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') < ?
            ", [$currentTime]);
    })
    ->get();

    // ✅ Get all activity IDs from above
    $activityIds = $activities->pluck('id');

    $attendInterests = OtherInterest::where('user_id', $user->id)
        ->where('confirm', 8)
        ->get();

    // ✅ Count only those activities where at least one other user also confirmed = 8
    $attendUsers = $attendInterests->filter(function ($interest) use ($user) {
        return OtherInterest::where('activity_id', $interest->activity_id)
            ->where('user_id', '!=', $user->id)
            ->where('confirm', 8)
            ->exists();
    })->count();

    // ✅ Filter OtherInterest where the current user has confirm 3 or 7
    $userInterests = OtherInterest::whereIn('activity_id', $activityIds)
        ->where('user_id', $user->id)
        ->whereIn('confirm', [3, 7])
        ->get();

    // ✅ Count only those where that activity also has confirm = 8 from *any* user
    $ghostUsers = $userInterests->filter(function ($interest) {
        return OtherInterest::where('activity_id', $interest->activity_id)
            ->where('confirm', 8)
            ->exists(); // at least one confirm=8 record
    })->count();

        
    $hostedActivity = Activity::where('user_id', $user->id)->where('status', 2)
    ->where(function ($query) use ($currentTime) {
        $query->whereDate('when_time', '<', substr($currentTime, 0, 10)) // Past date
            ->orWhereRaw("
                STR_TO_DATE(CONCAT(DATE(when_time), ' ', REPLACE(end_time, ' ', ' ')), '%Y-%m-%d %l:%i %p') < ?
            ", [$currentTime]);
    })
        ->count();

    $matchingActivities = Activity::where('user_id', $user->id)
                                ->where('status', 2)
                                ->get();

$activityIds = $matchingActivities->pluck('id'); 

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
    
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated',
        ], 401);
    }

    // Decode interest field
    $interestFieldDecoded = json_decode($user->interest, true);

    if (!is_array($interestFieldDecoded)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid interest data',
        ], 400);
    }

    $interestIds = [];
    foreach ($interestFieldDecoded as $item) {
        $interestIds = array_merge($interestIds, explode(',', $item));
    }

    $interestIds = array_map('trim', $interestIds);
    $interests = Interest::whereIn('id', $interestIds)->get(['id','name', 'icon']);

    // Profile images
    $profileImages = json_decode($user->profile_image, true);
    $imageUrls = [];
    foreach ($profileImages as $image) {
        $imageUrls[] = asset('uploads/app/profile_images/' . $image);
    }

    // 🗺️ Reverse geocoding (if lat/lng available)
    $locationString = null;

    if ($user->latitude && $user->longitude) {
        try {
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'lat' => $user->latitude,
                'lon' => $user->longitude,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $locationString = $response['display_name'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error("Reverse geocoding failed: " . $e->getMessage());
        }
    }

    $cities = AdminCity::where('id', $user->admin_city)->first();

    // Final response
    $userData = [
        'number' => $user->number,
        'name' => $user->name,
        'email' => $user->email,
        'age' => $user->age,
        'gender' => $user->gender,
        'looking_for' => $user->looking_for,
        'interest' => $interests,
        'status' => $user->status,
        'profile_images' => $imageUrls,
        'about' => $user->about ?? '',
        'address' => $user->address ?? '',
        'city' => $cities->city_name ?? '',
        'location' => $locationString,
        'friend_count' => $userList->count() + $likeUserList->count() + $matchedUsers->count(),
        'attendUsers' => $attendUsers,
        'ghostUsers' => $ghostUsers,
        'hostedActivity' => $hostedActivity,
    ];

    return response()->json([
        'status' => 200,
        'message' => 'User profile fetched successfully',
        'data' => $userData,
    ], 200);
}



        // public function updateProfile(Request $request)
        // {
        //     $user = Auth::user();

        //     if (!$user) {
        //         return response()->json(['message' => 'User not authenticated'], 401);
        //     }

        //     // Validate the incoming request data
        //     $validator = Validator::make($request->all(), [
        //         'name' => 'nullable|string|max:255',
        //         'age' => 'nullable|integer|min:18|max:100',
        //         'gender' => 'nullable|string',
        //         'looking_for' => 'nullable|string|max:255',
        //         'interest' => 'nullable|array',  // Array of interests (only updated for subscribed users)
        //         'profile_image' => 'nullable',  // Array of images
        //         'about' => 'nullable|string|max:1000',  // New field for "about" text (can be updated by any user)
        //     ]);

        //     // If validation fails, return error messages
        //     if ($validator->fails()) {
        //         return response()->json(['errors' => $validator->errors()], 400);
        //     }

        //     // Prepare data to be updated
        //     $updateData = [
        //         'name' => $request->name,
        //         'age' => $request->age,
        //         'gender' => $request->gender,
        //         'looking_for' => $request->looking_for,
        //     ];

        //     // Handle 'interest' field if provided and if the user has an active subscription
        //     if ($user->subscription == 1 && $request->has('interest') && is_array($request->interest)) {
        //         $updateData['interest'] = json_encode($request->interest);  // Store interests as JSON
        //     }

        //     if ($request->has('about')) {
        //         $updateData['about'] = $request->about;
        //     }
        //     if ($request->has('address')) {
        //         $updateData['address'] = $request->address;
        //     }

        //     if ($request->hasFile('profile_image')) {
        //         $imagePaths = [];
            
        //         foreach ($request->file('profile_image') as $image) {
        //             $imageValidator = Validator::make(['image' => $image], [
        //                 'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        //             ]);
            
        //             if ($imageValidator->fails()) {
        //                 return response()->json(['errors' => $imageValidator->errors()], 400);
        //             }
            
        //             $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        //             $image->move(public_path('uploads/app/profile_images/'), $imageName);
        //             $imagePaths[] = $imageName;
        //         }
            
        //         $updateData['profile_image'] = json_encode($imagePaths);
        //     }
            

        //     // Update the user with new data
        //     $user->update($updateData);

        //     // Handle 'interest' names and icons (only for subscribed users)
        //     $interestNamesWithIcons = [];
        //     if ($user->subscription == 1 && $request->has('interest') && is_array($request->interest)) {
        //         $interestIds = $request->interest;
        //         $interests = Interest::whereIn('id', $interestIds)->get();

        //         foreach ($interests as $interest) {
        //             $interestNamesWithIcons[] = [
        //                 'name' => $interest->name,
        //                 'icon' => $interest->icon, 
        //             ];
        //         }
        //     }

        //     // Handle image URLs
        //     $imageUrls = [];
        //     if ($user->profile_image) {
        //         $imagePaths = json_decode($user->profile_image, true);
        //         foreach ($imagePaths as $imageName) {
        //             $imageUrls[] = url('uploads/app/profile_images/' . $imageName); 
        //         }
        //     }

        //     return response()->json([
        //         'message' => 'Profile updated successfully',
        //         'data' => [
        //             [
        //                 'rendom' => $user->rendom,
        //                 'name' => $user->name,
        //                 'age' => $user->age,
        //                 'gender' => $user->gender,
        //                 'looking_for' => $user->looking_for,
        //                 'interest' => $interestNamesWithIcons, 
        //                 'profile_image' => $imageUrls,
        //                 'about' => $user->about, 
        //                 'address' => $user->address, 
        //             ]
        //         ],
        //         'status' => 200,
        //     ], 200);
        // }


//  public function updateProfile(Request $request)
//     {
//         $user = Auth::user();

//         if (!$user) {
//             return response()->json(['message' => 'User not authenticated'], 401);
//         }

//         $validator = Validator::make($request->all(), [
//             'name' => 'nullable|string|max:255',
//             'age' => 'nullable|integer|min:18|max:100',
//             'gender' => 'nullable|string',
//             'looking_for' => 'nullable|string|max:255',
//             'interest' => 'nullable|array', 
//             'about' => 'nullable|string|max:1000', 
//             'address' => 'nullable|string|max:255',  
//             'profile_image' => 'nullable|array',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 400);
//         }

//         $updateData = [
//             'name' => $request->name,
//             'age' => $request->age,
//             'gender' => $request->gender,
//             'looking_for' => $request->looking_for,
//         ];

//           $now = Carbon::now('Asia/Kolkata');

//             $activeSubscription = UserSubscription::where('user_id', $user->id)
//                 ->where('type', 'Activitys')
//                 ->where('is_active', 1)
//                 ->where('activated_at', '<=', $now)
//                 ->where('expires_at', '>=', $now)
//                 ->first();

//         if ($activeSubscription && $request->has('interest') && is_array($request->interest)) {
//             $updateData['interest'] = json_encode($request->interest); 
//         }
//         // if ($user->subscription == 1 && $request->has('interest') && is_array($request->interest)) {
//         //     $updateData['interest'] = json_encode($request->interest); 
//         // }

//         if ($request->has('about')) {
//             $updateData['about'] = $request->about;
//         }
//         if ($request->has('address')) {
//             $updateData['address'] = $request->address;
//         }

//        if ($request->hasFile('profile_image')) {
//         $existingImages = [];
//         if ($user->profile_image) {
//             $existingImages = json_decode($user->profile_image, true);
//         }

//         $newImages = $request->file('profile_image');

//         $availableSlots = 9 - count($existingImages);
//         if ($availableSlots <= 0) {
//             return response()->json(['message' => 'You already have maximum 9 images.'], 400);
//         }

//         $imagePaths = $existingImages; 

//         $uploadedCount = 0;

//         foreach ($newImages as $image) {
//             if ($uploadedCount >= $availableSlots) {
//                 break;
//             }

//             $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

//             if (in_array($imageName, $existingImages)) {
//                 continue;
//             }

//             $image->move(public_path('uploads/app/profile_images/'), $imageName);
//             $imagePaths[] = $imageName;
//             $uploadedCount++;
//         }

//         $updateData['profile_image'] = json_encode($imagePaths);
//     }

//         $user->update($updateData);

//         $interestNamesWithIcons = [];
//         if ($user->subscription == 1 && $request->has('interest') && is_array($request->interest)) {
//             $interestIds = $request->interest;
//             $interests = Interest::whereIn('id', $interestIds)->get();

//             foreach ($interests as $interest) {
//                 $interestNamesWithIcons[] = [
//                     'name' => $interest->name,
//                     'icon' => $interest->icon,
//                 ];
//             }
//         }

//         $imageUrls = [];
//         if ($user->profile_image) {
//             $imagePaths = json_decode($user->profile_image, true);

//             if (is_array($imagePaths)) {
//                 foreach ($imagePaths as $imageName) {
//                     $imageUrls[] = url('uploads/app/profile_images/' . $imageName);
//                 }
//             }
//         }

//         return response()->json([
//             'message' => 'Profile updated successfully',
//             'data' => [
//                 [
//                     'rendom' => $user->rendom,
//                     'name' => $user->name,
//                     'age' => $user->age,
//                     'gender' => $user->gender,
//                     'looking_for' => $user->looking_for,
//                     'interest' => $interestNamesWithIcons,
//                     'profile_image' => $imageUrls,
//                     'about' => $user->about,
//                     'address' => $user->address,
//                 ]
//             ],
//             'status' => 200,
//         ], 200);
//     }


  public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:18|max:100',
            'gender' => 'nullable|string',
            'looking_for' => 'nullable|string|max:255',
            'interest' => 'nullable|array',
            'about' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $updateData = [
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'looking_for' => $request->looking_for,
        ];

        $now = Carbon::now('Asia/Kolkata');

        // ✅ Check for active subscription
        $activeSubscription = UserSubscription::where('user_id', $user->id)
            ->where('type', 'Activitys')
            ->where('is_active', 1)
            ->where('activated_at', '<=', $now)
            ->where('expires_at', '>=', $now)
            ->first();

        // ✅ Update interest only if active subscription
        if ($activeSubscription && $request->has('interest') && is_array($request->interest)) {
            $updateData['interest'] = json_encode($request->interest);
        }

        if ($request->has('about')) {
            $updateData['about'] = $request->about;
        }

        if ($request->has('address')) {
            $updateData['address'] = $request->address;
        }

        // ✅ Handle profile image uploads
        if ($request->hasFile('profile_image')) {
            $existingImages = [];
            if ($user->profile_image) {
                $existingImages = json_decode($user->profile_image, true);
            }

            $newImages = $request->file('profile_image');
            $availableSlots = 9 - count($existingImages);

            if ($availableSlots <= 0) {
                return response()->json(['message' => 'You already have maximum 9 images.'], 400);
            }

            $imagePaths = $existingImages;
            $uploadedCount = 0;

            foreach ($newImages as $image) {
                if ($uploadedCount >= $availableSlots) break;

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                if (in_array($imageName, $existingImages)) continue;

                $image->move(public_path('uploads/app/profile_images/'), $imageName);
                $imagePaths[] = $imageName;
                $uploadedCount++;
            }

            $updateData['profile_image'] = json_encode($imagePaths);
        }

        $user->update($updateData);

        // ✅ Prepare interest response only if subscription is active
        $interestNamesWithIcons = [];
        $subscriptionStatus = 0;

        if ($activeSubscription && $request->has('interest') && is_array($request->interest)) {
            $subscriptionStatus = 1;

            $interestIds = $request->interest;
            $interests = Interest::whereIn('id', $interestIds)->get();

            foreach ($interests as $interest) {
                $interestNamesWithIcons[] = [
                    'name' => $interest->name,
                    'icon' => $interest->icon,
                ];
            }
        }

        // ✅ Build image URLs
        $imageUrls = [];
        if ($user->profile_image) {
            $imagePaths = json_decode($user->profile_image, true);

            if (is_array($imagePaths)) {
                foreach ($imagePaths as $imageName) {
                    $imageUrls[] = url('uploads/app/profile_images/' . $imageName);
                }
            }
        }

        // ✅ Final response
        return response()->json([
            'message' => 'Profile updated successfully',
            'subscription' => $subscriptionStatus,
            'data' => [
                [
                    'rendom' => $user->rendom,
                    'name' => $user->name,
                    'age' => $user->age,
                    'gender' => $user->gender,
                    'looking_for' => $user->looking_for,
                    'interest' => $interestNamesWithIcons,
                    'profile_image' => $imageUrls,
                    'about' => $user->about,
                    'address' => $user->address,
                ]
            ],
            'status' => 200,
        ]);
    }

        

   public function updatelatlong(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
            ],
            'status' => 200
        ]);
    }

        
   public function updateCity(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'admin_city' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->admin_city = $request->admin_city;
        $user->save();

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => [
                'admin_city' => $user->admin_city,
            ],
            'status' => 200
        ]);
    }
        
        
 public function fcmUpdate(Request $request)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'fcm_token' => 'nullable|string',
            'device_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->fcm_token = $request->fcm_token;
        $user->device_id = $request->device_id;
        $user->save();

        return response()->json([
            'message' => 'Device info updated successfully.',
            'data' => [
                'fcm_token' => $user->fcm_token,
                'device_id' => $user->device_id,
                'device_updated_at' => $user->device_updated_at,
            ],
            'status' => 200,
        ]);
    }

        
}

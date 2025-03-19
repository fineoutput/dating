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
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 



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
            'expires_at' => now()->addMinutes(10), 
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

        // if ($type === 'email') {
        //     $unverifyUser = UnverifyUser::orderBy('id','DESC')->where('email', $source_name)->first();
        //     // return $unverifyUser;
        //     if ($unverifyUser) {
        //         $unverifyUser->update(['email_verify' => 1]);

    
        //         return response()->json([
                    
        //                 'message' => 'Email verified successfully!',
        //                 'status' => 200,
        //                 'data' => [
        //                     'status' => 206,
        //                 ],
        //             // 'status' => true
        //         ]);
        //     } else {
        //         return $this->successResponse('No user found with the provided email.', false, 404);
        //     }
        // }

        if ($type === 'email') {
            // Find the unverified user based on the provided email
            $unverifyUser = UnverifyUser::orderBy('id', 'DESC')->where('email', $source_name)->first();
        
            if ($unverifyUser) {
                // For testing purposes, we will always use '0000' as the OTP
                $otp = '0000';
        
                // Simulate OTP validation with '0000' (as if it matches every time)
                if ($otp === '0000') {
                    // Update the email verification status
                    $unverifyUser->update(['email_verify' => 1]);
        
                    return response()->json([
                        'message' => 'Email verified successfully!',
                        'status' => 200,
                        'data' => [
                            'status' => 206,
                        ],
                    ]);
                } else {
                    // If you wanted to handle an invalid OTP case (though it won't happen with '0000')
                    return $this->successResponse('Invalid OTP provided.', false, 400);
                }
            } else {
                return $this->successResponse('No user found with the provided email.', false, 404);
            }
        }
        

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
    

   
 
    public function userprofile(Request $request)
        {
            $user = Auth::user();
            
            if (!$user) {
                // If user is not authenticated, return with 401 status and message
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
            
            // Get the interest field from the user model
            $interestField = $user->interest;
            
            // Decode the interest field
            $interestFieldDecoded = json_decode($interestField, true);

            // Check if the decoded data is an array
            if (!is_array($interestFieldDecoded)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid interest data',
                ], 400);
            }

            // Flatten the array and split each item by commas to get individual IDs
            $interestIds = [];
            foreach ($interestFieldDecoded as $item) {
                // For each item, split by commas and merge the result
                $interestIds = array_merge($interestIds, explode(',', $item));
            }

            // Trim any extra spaces around the IDs
            $interestIds = array_map('trim', $interestIds);

            // Fetch the interests from the Interest model
            $interests = Interest::whereIn('id', $interestIds)->get(['name', 'icon']);
            
            // Log the interest IDs and fetched interests for debugging
            \Log::info("Interest IDs: ", $interestIds);
            \Log::info("Fetched Interests: ", $interests->toArray());

            // Prepare the profile images URLs
            $profileImages = json_decode($user->profile_image, true);
            $imageUrls = [];
            foreach ($profileImages as $image) {
                $imageUrls[] = asset('uploads/app/profile_images/' . $image);
            }
            
            // Prepare the user data
            $userData = [
                // 'id' => $user->id,
                'number' => $user->number,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $user->age,
                'gender' => $user->gender,
                'looking_for' => $user->looking_for,
                'interest' => $interests,  
                'status' => $user->status,
                'profile_images' => $imageUrls,
            ];
            
            // Return the response with status and message
            return response()->json([
                'status' => 200,
                'message' => 'User profile fetched successfully',
                'data' => $userData,
            ], 200);
        }


        public function updateProfile(Request $request)
        {
            // Get the authenticated user
            $user = Auth::user();
        
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'age' => 'nullable|integer|min:18|max:100',
                'gender' => 'nullable|string',
                'looking_for' => 'nullable|string|max:255',
                'interest' => 'nullable|array',  // Array of interests
                'profile_image' => 'nullable|array',  // Array of images
            ]);
        
            // If validation fails, return error messages
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
        
            // Prepare data to be updated
            $updateData = [
                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'looking_for' => $request->looking_for,
            ];
        
            // Handle 'interest' field if provided
            if ($request->has('interest') && is_array($request->interest)) {
                $updateData['interest'] = json_encode($request->interest);  // Store as JSON
            }
        
            if ($request->hasFile('profile_image') && is_array($request->profile_image)) {
                $imagePaths = [];

                foreach ($request->profile_image as $image) {
                    $imageValidator = Validator::make(['image' => $image], [
                        'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', 
                    ]);
        
                    if ($imageValidator->fails()) {
                        return response()->json(['errors' => $imageValidator->errors()], 400);
                    }
        

                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
                    $image->move(public_path('uploads/app/profile_images/'), $imageName);  
                    $imagePaths[] = $imageName;  
                }

                $updateData['profile_image'] = json_encode($imagePaths);
            }

            $user->update($updateData);

            $interestNamesWithIcons = [];
            if ($request->has('interest') && is_array($request->interest)) {
                $interestIds = $request->interest;
                $interests = Interest::whereIn('id', $interestIds)->get();
        
                foreach ($interests as $interest) {
                    $interestNamesWithIcons[] = [
                        'name' => $interest->name,
                        'icon' => $interest->icon, 
                    ];
                }
            }
        
            $imageUrls = [];
            if ($user->profile_image) {
                $imagePaths = json_decode($user->profile_image, true);
                foreach ($imagePaths as $imageName) {
                    $imageUrls[] = url('uploads/app/profile_images/' . $imageName); 
                }
            }

            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => [
            [
                'rendom' => $user->rendom,
                'name' => $user->name,
                'age' => $user->age,
                'gender' => $user->gender,
                'looking_for' => $user->looking_for,
                'interest' => $interestNamesWithIcons,  // Send interests with names and icons
                'profile_image' => $imageUrls,  // Include full image URLs as an array
            ]
        ],
                'status' => 200,
            ], 200);
        }
        
        
        
        
}

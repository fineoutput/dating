<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class VibeController extends Controller
{

    public function index()
    {
        $data['vibe'] = Vibes::where('status', 1)->get();
        $data['tital'] = 'Vibes';
      return vieW('admin/vibe/index',$data);
    }

}

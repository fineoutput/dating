<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Redirect;
use Laravel\Sanctum\PersonalAccessToken;
use DateTime;


class HomeController extends Controller
{
    // ============================= START INDEX ============================ 
    public function index(Request $req)
    {
     
        return view('welcome')->withTitle('');
    }
    public function contact(Request $req)
    {
     
        return view('contact');
    }
    public function about(Request $req)
    {
     
        return view('about');
    }
    public function privacy_policy(Request $req)
    {
     
        return view('privacy_policy');
    }
    
}

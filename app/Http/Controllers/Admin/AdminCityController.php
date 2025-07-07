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
use App\Models\AdminCity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class AdminCityController extends Controller
{

    public function index()
    {
        $data['vibe'] = AdminCity::orderBy('id','DESC')->get();
        $data['tital'] = 'City';
      return view('admin.admincity.index',$data);
    }


    public function create()
    {
        $tital = "City";
        return view('admin.admincity.create', compact('tital'));
    }

    

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'city_name' => 'required',
        ]);

        $interest = AdminCity::create([
            'city_name' => $validated['city_name'],
            'status' => 1,
        ]);

        return redirect()->route('admin_city.index')->with('success', 'City added successfully!');
    }

    public function edit($id)
    {
        $interest = AdminCity::findOrFail($id);
        $tital = "City";
        return view('admin.admincity.create', compact('interest','tital'));
    }


    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'city_name' => 'required|string|max:255',
        ]);

        $interest = AdminCity::findOrFail($id);

        $interest->city_name = $validated['city_name'];
        $interest->save();

        return redirect()->route('admin_city.index')->with('success', 'Data updated successfully!');
    }


    public function destroy($id)
    {
        $interest = AdminCity::findOrFail($id);
        $interest->delete();

        return redirect()->back()->with('success', 'City deleted successfully!');

    }


    
    public function updateStatus($id)
    {
        $interest = AdminCity::findOrFail($id);
        $interest->status = ($interest->status == 1) ? 2 : 1;

        $interest->save();
        return redirect()->back()->with('success', 'Vibe status updated successfully!');
    }

}

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
        $data['vibe'] = Vibes::orderBy('id','DESC')->get();
        $data['tital'] = 'Vibes';
      return view('admin.vibe.index',$data);
    }


    public function create()
    {
        // dd('sdfa');
        $tital = "Interests";
        return view('admin.vibe.create', compact('tital'));
    }

    

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
        ]);

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/app/int_images'), $imageName);
            $imagePaths[] = 'uploads/app/int_images/' . $imageName;
        }

        // Create the new interest
        $interest = Vibes::create([
            'name' => $validated['name'],
            'icon' => $request->icon,
            'status' => 1,
        ]);

        return redirect()->route('vibe.index')->with('success', 'Vibe added successfully!');
    }

    public function edit($id)
    {
        $interest = Vibes::findOrFail($id);
        $tital = "Interests";
        return view('admin.vibe.create', compact('interest','tital'));
    }


    public function update(Request $request, $id)
    {
        
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
        ]);

        $interest = Vibes::findOrFail($id);

        $interest->name = $validated['name'];
        $interest->icon = $request->icon;
        $interest->save();

        return redirect()->route('vibe.index')->with('success', 'Data updated successfully!');
    }


    public function destroy($id)
    {
        $interest = Vibes::findOrFail($id);
        // Delete the interest record
        $interest->delete();

        return redirect()->back()->with('success', 'Vibe deleted successfully!');

    }


    
    public function updateStatus($id)
    {
        $interest = Vibes::findOrFail($id);
        $interest->status = ($interest->status == 1) ? 2 : 1;

        $interest->save();
        return redirect()->back()->with('success', 'Vibe status updated successfully!');
    }

}

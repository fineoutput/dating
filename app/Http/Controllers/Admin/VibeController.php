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
            'image' => 'required',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/app/int_images');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $imagePath = 'uploads/app/int_images/' . $imageName;
        }

        $interest = Vibes::create([
            'name' => $validated['name'],
            'image' => $request->image,
            'icon' => $imagePath, 
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
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable',
        ]);

        $interest = Vibes::findOrFail($id);

        $interest->name = $validated['name'];
        $interest->image = $request->image;

        // Handle new image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/app/int_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $newImagePath = 'uploads/app/int_images/' . $imageName;

            // Delete old image if it exists
            if ($interest->icon && file_exists(public_path($interest->icon))) {
                @unlink(public_path($interest->icon));
            }

            $interest->icon = $newImagePath;
        }

        $interest->save();

        return redirect()->route('vibe.index')->with('success', 'Vibe updated successfully!');
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

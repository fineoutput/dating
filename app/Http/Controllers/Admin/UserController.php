<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Citys;
use App\Models\Interest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::orderBy('id','DESC')->get(); // Fetch all users
        $title = "Users";
        return view('admin.Users.view_users', compact('data', 'title'));
    }

    /**
     * Show the form for creating or editing the specified resource.
     *
     * @param  int|null  $id
     * @return \Illuminate\Http\Response
     */
    public function createOrEdit($id = null)
    {
        $user = null;
        if ($id) {
            $user = User::find($id); // Fetch the user if editing
        }
        // dd($user);
        $interests  = Interest::all()->where('status',1);
        $states = State::all();
        $title = $id ? "Edit User" : "Add User";
        return view('admin.Users.add_user', compact('user','title','interests','states'));
    }

    /**
     * Store a newly created or update an existing user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        // Validation rules
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|unique:users,number',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'age' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'looking_for' => 'required|string|max:255',
            'state' => 'required|exists:all_states,id',  
            'city' => 'required|exists:all_cities,id',  
            'interest' => 'required|array',
            'interest.*' => 'required|exists:interests,id',  // Check that each selected interest exists
            'profile_image' => 'nullable|array',
            'profile_image.*' => 'required|image',
            'password' => 'nullable|string|min:6', // Optional, for update scenarios
        ]);

        if ($id) {
            $user = User::find($id);
            if (!$user) {
                return redirect()->back()->withErrors(['User not found']);
            }
            if ($user->profile_image && $request->hasFile('profile_image')) {
                $oldImages = json_decode($user->profile_image);
                foreach ($oldImages as $oldImage) {
                    $imagePath = public_path('uploads/app/profile_images/' . $oldImage);
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the old image
                    }
                }
            }
            
        } else {
            $user = new User(); 
        }

        if ($request->hasFile('profile_image') && is_array($request->profile_image)) {
            $imagePaths = [];
            $index=1;
            foreach ($request->profile_image as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/app/profile_images'), $imageName);
                $imagePaths[$index] =  $imageName;
                $index++;
            }
            $user->profile_image = json_encode($imagePaths);
        }

            $user->name = $request->name;
            $user->number = $request->number;
            $user->email = $request->email;
            $user->age = $request->age;
            $user->gender = $request->gender;
            $user->looking_for = $request->looking_for;
            $user->state = $request->state;
            $user->city = $request->city; 
            $user->status = 1; 
            $user->password = $request->password; 
            $user->interest = json_encode($request->interest);
        // Save the user
        $user->save();

        return redirect()->route('users.index')->with('success', 'User ' . ($id ? 'updated' : 'added') . ' successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $user = User::findOrFail($id);
        if ($user->profile_image) {
            $oldImages = json_decode($user->profile_image);
            foreach ($oldImages as $oldImage) {
                $imagePath = public_path('uploads/app/profile_images/' . $oldImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the old image
                }
            }
        }
        $user->delete();
        return redirect()->route('users.index')->with('User delete successfully');;
    }

    public function getCitiesByState($stateId)
    {
    $cities = Citys::where('state_id', $stateId)->get(['id', 'city_name']);
    return response()->json(['cities' => $cities]);
    }


    public function updateStatus($action, $encodedId)
    {
     $userId = base64_decode($encodedId);
     $newStatus = ($action === 'active') ? 1 : 0;

   
        $user = User::find($userId); 
        if ($user) {
            $user->status = $newStatus;
            $user->save();

            return redirect()->back()->with('success', 'User status updated successfully.');
        }
        return redirect()->back()->with('error', 'User not found.');
}


    public function addcoin(Request $request,$id){
        $data['user'] = User::findOrFail($id);
        return view('admin.usercoin.addcoin',$data);

    }

    public function activityCoinView(Request $request){
        $data['user'] = User::where('status',1)->get(); 
        return view('admin.usercoin.viewcoin',$data);
    }

    public function createCoin(Request $request){
            
    }


}

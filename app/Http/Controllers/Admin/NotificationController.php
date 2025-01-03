<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{

    function deletenotification($id) {
        $data = Notification::where('id',$id)->delete();
        return redirect()->back();
    }
    
    function notifications(){
        $data['notification'] = Notification::all();
        return view('admin/Notification.index',$data);
    }

    function add_notification(Request $request){

        if($request->method()=='POST'){
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // If you're uploading an image
            ]);
    
            // Handle file upload (if 'image' is uploaded)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
            } else {
                $imagePath = null;  // If no image is uploaded
            }
    
            // Create a new notification
            Notification::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $imagePath,  // Store the image path if uploaded
            ]);
    
            return Redirect('/notifications')->with('success', 'Notification created successfully');
        }
        return view('admin/Notification.create');
    }

}

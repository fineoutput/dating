<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Interest::orderBy('id', 'desc')->get(); // Fetch all interests
        $tital = "Interests";
        return view('admin.interests.view_interest', compact('data','tital'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('sdfa');
        $tital = "Interests";
        return view('admin.interests.add_interest', compact('tital'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
            'desc' => 'nullable|string',
        ]);

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/app/int_images'), $imageName);
            $imagePaths[] = 'uploads/app/int_images/' . $imageName;
        }

        // Create the new interest
        $interest = Interest::create([
            'name' => $validated['name'],
            'icon' => $request->icon,
            'desc' => $validated['desc'],
        ]);

        return redirect()->route('interests.index')->with('success', 'Interest added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interest = Interest::findOrFail($id);
        $tital = "Interests";
        return view('admin.interests.add_interest', compact('interest','tital'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
            'desc' => 'nullable|string',
        ]);

        $interest = Interest::findOrFail($id);

        // Handle the icon upload if it exists and unlink the old one if necessary
        // if ($request->hasFile('icon')) {
        //     $image = $request->file('icon');
    
        //     $oldImagePath = public_path('uploads/app/int_images/' . $interest->icon);
        //     if ($interest->icon && file_exists($oldImagePath)) {
        //         unlink($oldImagePath);
        //     }
        //     $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('uploads/app/int_images'), $imageName);
        //     $interest->icon = $imageName;
        // }

        // Update the interest details
        $interest->name = $validated['name'];
        $interest->desc = $validated['desc'];
        $interest->icon = $request->icon;
        $interest->save();

        return redirect()->route('interests.index')->with('success', 'Data updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = Interest::findOrFail($id);
        if ($interest->icon) {
            $oldImagePath = public_path('uploads/app/int_images/' . $interest->icon);
            if ($interest->icon && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        // Delete the interest record
        $interest->delete();

        return redirect()->route('interests.index')->with('success', 'Interest deleted successfully!');

    }

    public function updateStatus($status, $encodedId)
    {
        $id = base64_decode($encodedId);
        $interest = Interest::find($id);
        if (!$interest) {
            return redirect()->back()->with('error', 'Record not found!');
        }
        if ($status === 'active') {
            $interest->status = 1; 
        } elseif ($status === 'inactive') {
            $interest->status = 0; 
        } else {
            return redirect()->back()->with('error', 'Invalid status!');
        }
        $interest->save();
        return redirect()->back()->with('success', 'Status updated successfully!');
    }
}

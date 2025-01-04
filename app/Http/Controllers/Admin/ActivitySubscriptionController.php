<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\DatingSubscription;
use App\Models\CoinCategory;
use App\Models\ActivitySubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivitySubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ActivitySubscription::with(['category', 'interests'])->orderBy('id', 'desc')->get();
        $tital = "Activity Subscription";
        return view('admin.Activitysubscription.index', compact('data','tital'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('sdfa');
        $interest = Interest::all()->where('status',1);
        $category = CoinCategory::all();
        $tital = "Activity Subscription";
        return view('admin.Activitysubscription.create', compact('tital','interest','category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      

        // Validate incoming data
        $validatedData = $request->validate([
            'interests_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
            'type' => 'required|in:free,paid', // Example types, adjust as needed
        ]);
        
        // Create a new ActivitySubscription
        $activity = new ActivitySubscription();
        $activity->title = $request->title;
        // $activity->cost = $request->cost;
        $activity->expire_days = $request->expire_days;
        $activity->description = $request->description;
        $activity->category_id = $request->category_id;
        $activity->type = $request->type;
        $activity->interests_id = implode(',', $request->interests_id); // Store as JSON
        $activity->save();
        

    // Redirect with success message
    return redirect()->route('activitysubscriptionindex')->with('success', 'Activity Subscription added successfully!');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interest = ActivitySubscription::findOrFail($id);
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

     public function edit($id)
     {
        $data['activity']  = ActivitySubscription::findOrFail($id); // Fetch all interests
        $data['categories']  = CoinCategory::all(); // Fetch all interests
        $data['interests']  = Interest::all()->where('status',1); // Fetch all interests
         $data['tital'] = "Activity Subscription Edit";
         return view('admin.Activitysubscription.edit', $data);
     }

    public function update(Request $request, $id)
    {
        
       
      // Validate the input data
      $validatedData = $request->validate([
        'interests_id' => 'required|array', // Validate as an array of interest IDs
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required', // Ensure the category exists
        'type' => 'required', // Example types, adjust as needed
    ]);

    // Find the ActivitySubscription by ID
    $activity = ActivitySubscription::findOrFail($id);

    // Update the activity fields
    $activity->title = $request->title;
    // $activity->cost = $request->cost;
    $activity->expire_days = $request->expire_days;
    $activity->description = $request->description;
    $activity->category_id = $request->category_id;
    $activity->type = $request->type;
    $activity->interests_id = implode(',', $request->interests_id); // Store interests as comma-separated values

    // Save the updated data
    $activity->save();

        return redirect()->route('activitysubscriptionindex')->with('success', 'Data updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = ActivitySubscription::findOrFail($id);
        $interest->delete();
    
        return redirect()->route('activitysubscriptionindex')->with('success', 'Item deleted successfully.');

    }

 public function updateStatus($status, $id)
{
    $decodedId = base64_decode($id);
    
    // Map status text to numeric values
    $newStatus = ($status === 'active') ? 1 : 2;

    $interest = ActivitySubscription::findOrFail($decodedId);
    $interest->status = $newStatus;
    $interest->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}
}

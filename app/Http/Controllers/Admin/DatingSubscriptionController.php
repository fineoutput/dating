<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\DatingSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DatingSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DatingSubscription::orderBy('id', 'desc')->get(); // Fetch all interests
        $tital = "Dating Feature Subscription";
        return view('admin.datingSubscription.index', compact('data','tital'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('sdfa');
        $tital = "Dating Feature Subscription";
        return view('admin.datingSubscription.create', compact('tital'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Debug the incoming request
    // dd($request->all());
    
    $validatedData = $request->validate([
        'expire_days' => 'required|integer|min:1',
        // 'free_dating_feature' => 'nullable|boolean',
        'unlimited_swipes' => 'nullable|boolean',
        'swipe_message' => 'nullable|boolean',
        'backtrack' => 'nullable|boolean',
        'access_admirers' => 'nullable|boolean',
    ]);

    $free = new DatingSubscription;

    // Set default values for checkboxes
    $free->fill($request->all());

    // Handle checkboxes and set defaults if null
    // $free->free_dating_feature = $request->free_dating_feature ?? 0;
    $free->unlimited_swipes = $request->unlimited_swipes ?? 0;
    $free->cupid_count = $request->cupid_count ?? 0;
    $free->swipe_message = $request->swipe_message ?? 0;
    $free->backtrack = $request->backtrack ?? 0;
    $free->access_admirers = $request->access_admirers ?? 0;

    // Save to the database
    $free->save();

    // Redirect with success message
    return redirect()->route('datingsubscriptionindex')->with('success', 'Coin Category added successfully!');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interest = DatingSubscription::findOrFail($id);
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
        $data['interest']  = DatingSubscription::findOrFail($id); // Fetch all interests
         $data['tital'] = " Dating Feature Subscription Edit";
         return view('admin.datingSubscription.edit', $data);
     }

    public function update(Request $request, $id)
    {
      // Validate the input data
      $request->validate([
        // 'expire_days' => 'required|integer',
        // 'cost' => 'required|numeric',
        // 'free_dating_feature' => 'nullable',
        'unlimited_swipes' => 'nullable',
        'swipe_message' => 'nullable',
        // 'backtrack' => 'nullable',
        // 'access_admirers' => 'nullable',
    ]);

    // Find the dating subscription by ID
    $interest = DatingSubscription::findOrFail($id);

    // Update the subscription with the validated data
    // $interest->type = $request->type;
    // $interest->free_dating_feature = $request->has('free_dating_feature') ? '1' : '0';
    $interest->unlimited_swipes = $request->unlimited_swipes;
    $interest->cupid_count = $request->cupid_count;
    $interest->swipe_message = $request->swipe_message;
    $interest->cost = $request->cost;
    $interest->expire_days = $request->expire_days;
    // $interest->backtrack = $request->backtrack;
    // $interest->access_admirers = $request->access_admirers;

    // Save the updated subscription
    $interest->save();


        return redirect()->route('datingsubscriptionindex')->with('success', 'Data updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = DatingSubscription::findOrFail($id);
        $interest->delete();
    
        return redirect()->route('datingsubscriptionindex')->with('success', 'Item deleted successfully.');

    }

 public function updateStatus($status, $id)
{
    $decodedId = base64_decode($id);
    
    // Map status text to numeric values
    $newStatus = ($status === 'active') ? 1 : 2;

    $interest = DatingSubscription::findOrFail($decodedId);
    $interest->status = $newStatus;
    $interest->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}
}

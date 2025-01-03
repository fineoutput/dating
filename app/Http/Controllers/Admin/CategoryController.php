<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\CoinCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CoinCategory::orderBy('id', 'desc')->get(); // Fetch all interests
        $tital = "Coin Category";
        return view('admin.coincategory.index', compact('data','tital'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('sdfa');
        $tital = "Coin Category";
        return view('admin.coincategory.create', compact('tital'));
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
            'category' => 'required|string|max:255',
            'feature' => 'required|string|max:255',
        ]);
        // Create the new interest
        $interest = CoinCategory::create([
            'category' => $request->category,
            'feature' => $request->feature,
            'bronze' => $request->bronze,
            'silver' => $request->silver,
            'gold' => $request->gold,
            'cost' => $request->cost,
            'description' => $request->description,
        ]);

        return redirect()->route('coinCategoryindex')->with('success', 'Coin Category added successfully!');
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

     public function edit($id)
     {
        $data['interest']  = CoinCategory::findOrFail($id); // Fetch all interests
         $data['tital'] = "Coin Category Edit";
         return view('admin.coincategory.edit', $data);
     }

    public function update(Request $request, $id)
    {
        
       
    $validated = $request->validate([
        'category'    => 'required|string|max:255',
        'feature'     => 'required|string|max:255',
        'bronze'      => 'nullable|numeric',
        'silver'      => 'nullable|numeric',
        'gold'        => 'nullable|numeric',
        'cost'        => 'nullable|numeric',
        'description' => 'nullable|string',
    ]);

    $interest = CoinCategory::findOrFail($id);

    // Assign validated data to the model
    $interest->category = $request->category;
    $interest->feature = $request->feature;
    $interest->bronze = $request->bronze;
    $interest->silver = $request->silver;
    $interest->gold = $request->gold;
    $interest->cost = $request->cost;
    $interest->description = $request->description;

    // Save the updated model
    $interest->save();


        return redirect()->route('coinCategoryindex')->with('success', 'Data updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interest = CoinCategory::findOrFail($id);
        $interest->delete();
    
        return redirect()->route('coinCategoryindex')->with('success', 'Item deleted successfully.');

    }

 public function updateStatus($status, $id)
{
    $decodedId = base64_decode($id);
    
    // Map status text to numeric values
    $newStatus = ($status === 'active') ? 1 : 2;

    $interest = CoinCategory::findOrFail($decodedId);
    $interest->status = $newStatus;
    $interest->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}
}

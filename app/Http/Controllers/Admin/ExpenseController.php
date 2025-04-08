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
use App\Models\Expense;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class ExpenseController extends Controller
{

    public function index()
    {
        $data['expense'] = Expense::orderBy('id','DESC')->get();
        $data['tital'] = 'Expense';
      return view('admin.expense.index',$data);
    }


    
    public function create()
    {
        // dd('sdfa');
        $tital = "Interests";
        return view('admin.expense.create', compact('tital'));
    }

    

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
        ]);

        // Create the new interest
        $interest = Expense::create([
            'name' => $validated['name'],
            'icon' => $request->icon,
            'status' => 1,
        ]);

        return redirect()->route('expense.index')->with('success', 'Expense added successfully!');
    }

    public function edit($id)
    {
        $interest = Expense::findOrFail($id);
        $tital = "Interests";
        return view('admin.expense.create', compact('interest','tital'));
    }


    public function update(Request $request, $id)
    {
        
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
        ]);

        $interest = Expense::findOrFail($id);

        $interest->name = $validated['name'];
        $interest->icon = $request->icon;
        $interest->save();

        return redirect()->route('expense.index')->with('success', 'Data updated successfully!');
    }


    public function destroy($id)
    {
        $interest = Expense::findOrFail($id);
        // Delete the interest record
        $interest->delete();

        return redirect()->back()->with('success', 'Vibe deleted successfully!');

    }


    
    public function updateStatus($id)
    {
        $interest = Expense::findOrFail($id);
        $interest->status = ($interest->status == 1) ? 2 : 1;

        $interest->save();
        return redirect()->back()->with('success', 'Vibe status updated successfully!');
    }

}

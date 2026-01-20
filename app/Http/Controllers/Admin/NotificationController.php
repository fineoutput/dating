<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Services\FirebaseService;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    function deletenotification($id) {
        $data = Notification::where('id',$id)->delete();
        return redirect()->back();
    }
    
    function notifications(){
        $data['notification'] = Notification::all();
        return view('admin/Notification.index',$data);
    }

  function add_notification(Request $request)
    {
        if ($request->method() == 'POST') {

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image',
            ]);

            // 📸 Image upload (public path)
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('images');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $image->move($destinationPath, $fileName);
                $imagePath = 'images/' . $fileName;
            } else {
                $imagePath = null;
            }

            // 💾 Save notification in DB
            $notification = Notification::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imagePath,
            ]);

            /* ================= FIREBASE PUSH ================= */

            try {
                $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

                $messaging = $firebase->createMessaging();

                // 🔔 Topic name (FIXED)
                $topic = 'DidYou';

                $imageUrl = $imagePath ? asset($imagePath) : null;

                // Notification payload
                $notificationPayload = [
                    'title' => $request->title,
                    'body'  => $request->description,
                    'image' => $imageUrl,
                ];

                // Data payload (optional)
                $dataPayload = [
                    'screen' => 'DidYou',
                    'image'  => $imageUrl,
                ];

                $cloudMessage = CloudMessage::withTarget('topic', $topic)
                    ->withNotification($notificationPayload)
                    ->withData($dataPayload);

                $messaging->send($cloudMessage);

            } catch (\Exception $e) {
                Log::error('DidYou FCM error: '.$e->getMessage());
            }

            /* ================================================= */

            return redirect('/notifications')
                ->with('success', 'Notification created & sent successfully');
        }

        return view('admin/Notification.create');
    }

   
}

<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/dymm-18784-firebase-adminsdk-fbsvc-e5698f329b.json'))
            ->createMessaging();
    }

 public function sendNotification(string $fcmToken, string $title, string $body, array $data = []): bool
{
    try {
        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $this->messaging->send($message);
 
        Log::info("FCM notification sent successfully to token: $fcmToken");
        Log::info("FCM notification Message Payload: ", ['payload' => $message->jsonSerialize()]);
        Log::info("Notification details", [
            'title' => $title,
            'body' => $body,
            'data' => $data
        ]);

        return true;
    } catch (\Throwable $e) {
        Log::error("❌ Failed to send FCM notification: " . $e->getMessage(), [
            'token' => $fcmToken,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);
        return false;
    }
}

}

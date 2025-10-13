<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;


class ChatMessageNotification extends Notification
{
   use Queueable;

    private $title;
    private $body;
    private $data;

    public function __construct($title, $body, $data = [])
    {
        $this->title = $title;
        $this->body = $body; // This should be a string for the notification message
        $this->data = $data; // This is the custom data array
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        try {
            return (new FcmMessage(
                notification: new FcmNotification(
                    title: $this->title,
                    body: $this->body,
                    image: ''
                )
            ))
            ->data($this->data)
            ->custom([
                'android' => [
                    'notification' => [
                        'sound' => 'default',
                        'color' => '#0A0A0A',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('FCM Notification Error: ' . $e->getMessage(), [
                'exception' => $e,
                'notifiable' => $notifiable,
                'title' => $this->title,
                'body' => $this->body,
                'data' => $this->data,
            ]);

            // Optionally rethrow or return a default message
            throw $e;
        }
    }
}
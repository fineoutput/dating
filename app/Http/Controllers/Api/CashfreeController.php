<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\PaymentLog;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class CashfreeController extends Controller
{


public function verifyPayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|string',
        'expire_time' => 'nullable|date',
    ]);

    $order_id = $request->order_id;
    $expire_time = $request->expire_time ? Carbon::parse($request->expire_time) : null;

    $res = Http::withHeaders([
        'x-client-id' => config('cashfree.app_id'),
        'x-client-secret' => config('cashfree.secret_key'),
        'x-api-version' => '2022-09-01',
        'Content-Type' => 'application/json',
    ])->get(config('cashfree.base_url') . "/orders/{$order_id}");

    if (!$res->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch order details.',
            'error' => $res->json(),
        ], 400);
    }

    $order = $res->json(); // ✅ Entire response is the order
    $payment_status = $order['order_status'] ?? null;

    $customer = $order['customer_details'] ?? [];
    $user = null;
    if (isset($customer['customer_email'])) {
        $user = User::where('email', $customer['customer_email'])->first();
    }

    PaymentLog::updateOrCreate(
        ['order_id' => $order_id],
        [
            'user_id' => $user?->id,
            'amount' => $order['order_amount'] ?? 0,
            'status' => $payment_status,
            'event_type' => 'VERIFY_PAYMENT',
            'raw_response' => json_encode($order),
        ]
    );

    if ($payment_status === 'ACTIVE') {
        $subscription = UserSubscription::where('order_id', $order_id)->first();

        if ($subscription && (!$subscription->is_active ?? true)) {
            $subscription->update([
                'is_active' => true,
                'activated_at' => now(),
                'expires_at' => $expire_time ?? now()->addDays($subscription->expire_days),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment verified and subscription activated.',
            'subscription' => $subscription,
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Payment not completed or inactive.',
        'payment_status' => $payment_status,
    ], 400);
}


    public function createSubscriptionOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'amount' => 'required|numeric',
            // 'email' => 'required|email',
            // 'phone' => 'required|string',
            'expire_days' => 'required', 
        ]);

            $user = Auth::user();
            // return $user;

        $order_id = 'SUB_' . uniqid();

        $res = Http::withHeaders([
            'x-client-id' => config('cashfree.app_id'),
            'x-client-secret' => config('cashfree.secret_key'),
            'x-api-version' => '2022-09-01',
            'Content-Type' => 'application/json',
        ])->post(config('cashfree.base_url') . '/orders', [
            'order_id' => $order_id,
            'order_amount' => $request->amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'CUST_' . uniqid(),
                'customer_email' => $user->email,
                'customer_phone' => $user->number,
            ],
        ]);

        if ($res->successful()) {

            // $user = User::where('email', $request->email)->first();
            $user = Auth::user();

            UserSubscription::create([
                'user_id' => $user ? $user->id : null,
                'amount' => $request->amount,
                'plan_id' => $request->plan_id,
                'expire_days' => $request->expire_days ?? 30,
                'order_id' => $order_id, 
            ]);

            $data = $res->json();

            return response()->json([
                'success' => true,
                'order_id' => $order_id,
                'payment_session_id' => $data['payment_session_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cashfree order creation failed.',
            'error' => $res->json(),
        ], 400);
    }


}

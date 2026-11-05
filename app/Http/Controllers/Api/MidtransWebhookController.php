<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use App\Services\MidtransService;

class MidtransWebhookController extends Controller
{
    public function __construct(MidtransService $midtransService)
    {
        // Instantiating MidtransService to set Midtrans config (ServerKey, IsProduction, etc.)
        // Config is set in the constructor of MidtransService
    }

    public function handle(Request $request)
    {
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error Processing Notification'], 400);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;
        $paymentType = $notification->payment_type;

        Log::info("Midtrans Notification received for Order ID: $orderId, Status: $transactionStatus");

        $paymentTransaction = PaymentTransaction::where('external_id', $orderId)->first();

        if (!$paymentTransaction) {
            Log::warning("PaymentTransaction not found for Order ID: $orderId");
            return response()->json(['message' => 'Transaction Not Found'], 404);
        }

        $donation = $paymentTransaction->donation;

        // Update payment transaction details
        $paymentTransaction->payment_method = $paymentType;
        $payload = is_array($paymentTransaction->payload) ? $paymentTransaction->payload : [];
        $payload['midtrans'] = $notification->getResponse();
        $paymentTransaction->payload = $payload;
        
        $donationStatus = 'pending';
        $transactionState = 'pending';

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transactionState = 'pending';
                $donationStatus = 'pending';
            } else if ($fraudStatus == 'accept') {
                $transactionState = 'paid';
                $donationStatus = 'paid';
            }
        } else if ($transactionStatus == 'settlement') {
            $transactionState = 'paid';
            $donationStatus = 'paid';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transactionState = 'failed';
            $donationStatus = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transactionState = 'pending';
            $donationStatus = 'pending';
        }

        $paymentTransaction->status = $transactionState;
        
        if ($transactionState == 'paid' && !$paymentTransaction->paid_at) {
            $paymentTransaction->paid_at = now();
            if ($donation) {
                $donation->paid_at = now();
            }
        }

        $paymentTransaction->save();

        if ($donation) {
            $donation->status = $donationStatus;
            $donation->save();
        }

        return response()->json(['message' => 'OK']);
    }
}

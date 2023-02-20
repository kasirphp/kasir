<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MidtransController extends Controller
{
    public function payment(Request $request)
    {
        $user = auth()->user();

        \Midtrans\Config::$serverKey = 'your_server_key';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Midtrans\Config::$appendNotifUrl = 'https://yourdomain.com/payment/notification';

        $transaction_details = [
            'order_id' => rand(),
            'gross_amount' => $request->get('amount'),
            'customer_details' => $user->toArray(),
        ];

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $user->cart()->items(),
        ];

        if ($request->get('payment_method') === 'credit_card') {
            $method = [
                'payment_type' => 'credit_card',
                'credit_card' => [
                    'token_id' => 'your_customer_card_token_id',
                    'authentication' => true,
                ],
            ];
        } elseif ($request->get('payment_method') === 'qris') {
            $method = [
                'payment_type' => 'qris',
                'qris' => [
                    'acquirer' => 'gopay',
                ],
            ];
        } // The logic goes on...

        $transaction_data = array_merge($transaction_data, $method);

        return \Midtrans\CoreApi::charge($transaction_data);
    }
}

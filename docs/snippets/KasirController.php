<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kasir\Kasir\Facades\Kasir;

class KasirController extends Controller
{
    public function payment(Request $request)
    {
        $user = auth()->user();

        // Server key, isProduction, isSanitized, is3ds,
        // and appendNotificationUrl is configured in the config file.
        // at config/kasir.php

        $kasir = Kasir::make()
            ->customerDetails($user)
            ->itemDetails($user->cart()->items());

        if ($request->get('payment_method') === 'credit_card') {
            $kasir->creditCard($request->get('token_id'), true);
        } elseif ($request->get('payment_method') === 'qris') {
            $kasir->qris('gopay');
        }

        return $kasir->charge();
    }
}

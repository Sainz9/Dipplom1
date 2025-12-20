<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        if (!auth()->check()) {
        return redirect()->back()->with('auth_error', 'Та эхлээд системд нэвтрэх шаардлагатай.');
    }
        $request->validate([
            'payment_method_ui' => 'required',
            'game_id' => 'required',
            'amount' => 'required',
        ]);

        $gameId = $request->input('game_id');
        $amount = $request->input('amount');
        $method = $request->input('payment_method_ui'); 
        $userId = auth()->id() ?? 1;

        $order = Order::create([
            'user_id' => $userId,
            'game_id' => $gameId,
            'amount' => $amount,
            'payment_method' => $method,
            'status' => 'pending',
        ]);

        return redirect()->route('payment.transfer', $order->id);
    }

    public function showTransfer($orderId)
    {
        $order = Order::with('game')->findOrFail($orderId);
        $bankInfo = [];

        // 1. QPAY СОНГОСОН ҮЕД -> QR ЗУРАГТАЙ
        if ($order->payment_method == 'qpay') {
            $bankInfo = [
                'name' => 'QPay QR',
                'account_name' => 'Өлзийтайван Анхбаяр', 
                'account_number' => '5014292560', 
                'color' => '#1b3a6e',
                'logo' => 'https://solongo.medsoft.care:3001/static/media/qpay-logo.96d8ccc6ff2a2c3a0010.png',
                
                // ЗУРАГ: public/img/qpay.jpg
                'qr_image' => asset('img/qpay.jpg') 
            ];
        } 
        
        // 2. БУСАД ҮЕД (ХААН БАНК) -> ЗӨВХӨН ДАНСНЫ МЭДЭЭЛЭЛ
        else {
            $bankInfo = [
                'name' => 'Хаан Банк',
                'account_name' => 'Өлзийтайван Анхбаяр', 
                'account_number' => '5014292560', 
                'color' => '#005541',
                'logo' => 'https://www.servicenow.com/content/dam/servicenow-assets/public/en-us/digital-graphics/ds-logos/logo-khan-bank-2.png',
                'qr_image' => null 
            ];
        }

        return view('payment-transfer', compact('order', 'bankInfo'));
    }

    public function confirm($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'checking']);
        return redirect()->route('payment.wait', $order->id);
    }

    public function wait($orderId)
    {
        return view('payment-wait');
    }

    public function success()
    {
        return view('payment-success');
    }
    
}
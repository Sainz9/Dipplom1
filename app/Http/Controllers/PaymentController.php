<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Game;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // 1. Хэрэглэгч нэвтэрсэн эсэхийг шалгах
        if (!auth()->check()) {
            return redirect()->back()->with('auth_error', 'Төлбөр төлөхийн тулд эхлээд нэвтэрнэ үү.');
        }

        $request->validate([
            'payment_method_ui' => 'required',
            'game_id' => 'required',
            'amount' => 'required',
        ]);

        $gameId = $request->input('game_id');
        $userId = auth()->id();

        // 2. ЗӨВХӨН "checking" эсвэл "paid" бол түгжинэ. 
        // "pending" үед дахин банк сонгох боломжтой байхаар тооцож шалгана.
        $lockedOrder = Order::where('user_id', $userId)
                              ->where('game_id', $gameId)
                              ->whereIn('status', ['paid', 'checking'])
                              ->first();

        if ($lockedOrder) {
            if ($lockedOrder->status == 'paid') {
                return redirect()->back()->with('auth_error', 'Танд энэ тоглоом аль хэдийн байна.');
            }
            return redirect()->back()->with('auth_error', 'Таны захиалга шалгагдаж байна. Түр хүлээнэ үү.');
        }

        // 3. Хэрэв өмнөх "pending" захиалга байвал түүнийг шинэчилнэ (Update), байхгүй бол үүсгэнэ (Create)
        // Ингэснээр хэрэглэгч банк сонгох бүрт баазад олон "pending" захиалга үүсэхгүй.
        $order = Order::updateOrCreate(
            [
                'user_id' => $userId,
                'game_id' => $gameId,
                'status' => 'pending'
            ],
            [
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method_ui'),
            ]
        );

        return redirect()->route('payment.transfer', $order->id);
    }

    public function showTransfer($orderId)
    {
        $order = Order::with('game')->findOrFail($orderId);
        $bankInfo = [];

        if ($order->payment_method == 'qpay') {
            $bankInfo = [
                'name' => 'QPay QR',
                'account_name' => 'Өлзийтайван Анхбаяр', 
                'account_number' => '88000500,5014292560', 
                'color' => '#1b3a6e',
                'logo' => 'https://solongo.medsoft.care:3001/static/media/qpay-logo.96d8ccc6ff2a2c3a0010.png',
                'qr_image' => asset('img/qpay.jpg') 
            ];
        } else {
            $bankInfo = [
                'name' => 'Хаан Банк',
                'account_name' => 'Өлзийтайван Анхбаяр', 
                'account_number' => '88000500,5014292560', 
                'color' => '#005541',
                'logo' => 'https://www.servicenow.com/content/dam/servicenow-assets/public/en-us/digital-graphics/ds-logos/logo-khan-bank-2.png',
                'qr_image' => null 
            ];
        }

        return view('payment-transfer', compact('order', 'bankInfo'));
    }

    public function checkout($id)
    {
        $game = Game::findOrFail($id);
        $existingOrder = null;

        if (auth()->check()) {
            // Хамгийн сүүлийн захиалгыг олно
            $existingOrder = Order::where('user_id', auth()->id())
                                  ->where('game_id', $id)
                                  ->latest()
                                  ->first();
        }

        return view('checkout', compact('game', 'existingOrder'));
    }

    public function confirm($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', auth()->id())->firstOrFail();
        
        // Статусыг 'checking' болгоно (Одоо л Checkout дээр "Шалгаж байна" гэж харагдана)
        $order->update(['status' => 'checking']);
        
        return redirect()->route('payment.wait', $order->id);
    }

    public function wait($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment-wait', compact('order'));
    }

    public function cancel($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', auth()->id())
                      ->where('status', 'pending')
                      ->firstOrFail();

        $order->delete();

        return redirect()->route('game.checkout', $order->game_id)->with('success', 'Захиалга цуцлагдлаа.');
    }
}
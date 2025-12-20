<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    // Нууц үг сэргээх хуудсыг харуулах
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Сэргээх линк илгээх (Form submit хийх үед)
    public function store(Request $request)
    {
        // 1. Имэйлийг шалгах
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. Линк илгээх (Laravel-ийн бэлэн функц)
        // Энэ нь таны .env дээрх MAIL тохиргоог ашиглана
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Хариу буцаах
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($status)]);
    }
}
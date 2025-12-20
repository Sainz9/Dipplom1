<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // 1. ХАДГАЛАХ
    public function store(Request $request) {
        // 1. Баталгаажуулалт
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // 2. Хадгалалт
        Review::create([
            'user_id' => auth()->id(),
            'game_id' => $request->game_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // 3. Зөв мессеж буцаах (Fragment нэмсэн тул доошоо үсэрнэ)
        return back()->withFragment('reviews-section');
    }

    // 2. ЗАСАХ
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ЭНД withFragment НЭМСЭН
        return back()->withFragment('reviews-section');
    }

    // 3. УСТГАХ
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && auth()->user()->usertype !== 'admin') {
            abort(403);
        }

        $review->delete();

        // ЭНД withFragment НЭМСЭН
        return back()->withFragment('reviews-section');
    }
}
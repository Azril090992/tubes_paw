<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Cafe;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'cafe'])->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cafe_id' => 'required|exists:cafes,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        $userId = auth()->id() ?? 1; // For now, using 1 as default user

        // Check if user already reviewed this cafe
        $existingReview = Review::where('user_id', $userId)
            ->where('cafe_id', $request->cafe_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this cafe');
        }

        Review::create([
            'user_id' => $userId,
            'cafe_id' => $request->cafe_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
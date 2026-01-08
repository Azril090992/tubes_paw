<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Cart;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    public function index(Request $request)
    {
        $query = Cafe::with('owner');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->has('distance') && $request->distance != '') {
            $query->where('distance', '<=', $request->distance);
        }

        $cafes = $query->paginate(12);
        return view('cafes.index', compact('cafes'));
    }

    public function create()
    {
        return view('cafes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required',
            'name' => 'required',
            'address' => 'required'
        ]);

        Cafe::create($request->all());
        return redirect()->route('cafes.index');
    }

    public function show(Cafe $cafe, Request $request)
    {
        $photos = json_decode($cafe->cafeDetail->photos ?? '[]');

        // Ambil review asli
        $reviews = $cafe->reviews()->with('user')->latest()->get();

        // Hitung summary rating
        $averageRating = $cafe->averageRating() ?? 0;
        $totalReviews = $reviews->count();
        $ratingCounts = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        $distance = $cafe->distance ? $cafe->distance . " km" : "-";

        $now = now();
        $isOpen = false;
        if ($cafe->status && $cafe->open_time && $cafe->close_time) {
            $openTime = \Carbon\Carbon::createFromFormat('H:i:s', $cafe->open_time);
            $closeTime = \Carbon\Carbon::createFromFormat('H:i:s', $cafe->close_time);
            
            if ($now->between($openTime, $closeTime)) {
                $isOpen = true;
            }
        }

        $menus = $cafe->menus()
            ->when($request->query('cat'), function ($q) use ($request) {
                $q->whereHas('category', function ($q) use ($request) {
                    $q->where('id', $request->query('cat'));
                });
            })
            ->get();

        $menu_categories = \App\Models\Category::whereHas('menus', function ($q) use ($cafe) {
            $q->where('cafe_id', $cafe->id);
        })->get();

        // Filter cart items hanya untuk cafe ini
        $cartItems = Cart::with('menu.cafe')
            ->forUser(auth()->id())
            ->whereHas('menu', function ($q) use ($cafe) {
                $q->where('cafe_id', $cafe->id);
            })
            ->get();

        $totalItems = $cartItems->sum('quantity');
        $total = $cartItems->sum('subtotal');

        $existingBookings = \App\Models\Booking::where('cafe_id', $cafe->id)
            ->where('arrival_time', '>=', now()->startOfDay())
            ->whereNotIn('status', ['cancelled', 'payment_rejected'])
            ->get()
            ->map(function ($booking) {
                return [
                    'table_id' => $booking->table_id,
                    'arrival_time' => $booking->arrival_time, // This casts to string usually or Carbon
                ];
            });

        return view('cafes.show', compact(
            'cafe',
            'photos',
            'distance',
            'isOpen',
            'menu_categories',
            'menus',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingCounts',
            'cartItems',
            'total',
            'totalItems',
            'existingBookings'
        ));
    }

    public function edit(Cafe $cafe)
    {
        return view('cafes.edit', compact('cafe'));
    }

    public function update(Request $request, Cafe $cafe)
    {
        $cafe->update($request->all());
        return redirect()->route('cafes.index');
    }

    public function destroy(Cafe $cafe)
    {
        $cafe->delete();
        return redirect()->route('cafes.index');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalBookings = \App\Models\Booking::count();
        $totalRevenue = \App\Models\Booking::where('status', 'confirmed')->sum('total_amount');
        $totalUsers = \App\Models\User::where('role', 'customer')->count();
        $recentBookings = \App\Models\Booking::with('user', 'cafe')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'totalRevenue', 'totalUsers', 'recentBookings'));
    }

    public function bookings(Request $request)
    {
        $bookings = \App\Models\Booking::with('user', 'cafe', 'table')
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filter_type, function ($q) use ($request) {
                if ($request->filter_type === 'schedule') {
                    return $q->where('arrival_time', '>=', now());
                }
                if ($request->filter_type === 'day' && $request->filter_date) {
                    return $q->whereDate('arrival_time', $request->filter_date);
                }
                if ($request->filter_type === 'month' && $request->filter_month) {
                    $parts = explode('-', $request->filter_month);
                    if (count($parts) === 2) {
                        return $q->whereYear('arrival_time', $parts[0])
                            ->whereMonth('arrival_time', $parts[1]);
                    }
                }
                if ($request->filter_type === 'range' && $request->start_date && $request->end_date) {
                    return $q->whereDate('arrival_time', '>=', $request->start_date)
                        ->whereDate('arrival_time', '<=', $request->end_date);
                }
            })
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function exportBookings(Request $request)
    {
        $bookings = \App\Models\Booking::with('user', 'cafe', 'table')
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->filter_type, function ($q) use ($request) {
                if ($request->filter_type === 'schedule') {
                    return $q->where('arrival_time', '>=', now());
                }
                if ($request->filter_type === 'day' && $request->filter_date) {
                    return $q->whereDate('arrival_time', $request->filter_date);
                }
                if ($request->filter_type === 'month' && $request->filter_month) {
                    $parts = explode('-', $request->filter_month);
                    if (count($parts) === 2) {
                        return $q->whereYear('arrival_time', $parts[0])
                            ->whereMonth('arrival_time', $parts[1]);
                    }
                }
                if ($request->filter_type === 'range' && $request->start_date && $request->end_date) {
                    return $q->whereDate('arrival_time', '>=', $request->start_date)
                        ->whereDate('arrival_time', '<=', $request->end_date);
                }
            })
            ->latest()
            ->get();

        return view('admin.bookings.export_pdf', compact('bookings'));
    }

    public function confirmPayment(\App\Models\Booking $booking)
    {
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Payment confirmed!');
    }

    public function rejectPayment(\App\Models\Booking $booking)
    {
        $booking->update([
            'status' => 'payment_rejected',
            'payment_status' => 'failed',
        ]);

        return back()->with('success', 'Payment rejected.');
    }

    public function cafes()
    {
        $cafes = \App\Models\Cafe::with('owner')->paginate(10);
        return view('admin.cafes.index', compact('cafes'));
    }

    public function users()
    {
        $users = \App\Models\User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function approveCafe(\App\Models\Cafe $cafe)
    {
        $cafe->update(['approval_status' => 'approved']);
        return back()->with('success', 'Cafe approved successfully.');
    }

    public function rejectCafe(\App\Models\Cafe $cafe)
    {
        $cafe->update(['approval_status' => 'rejected']);
        return back()->with('success', 'Cafe rejected.');
    }



    public function createCafe()
    {
        $categories = \App\Models\Category::all();
        return view('admin.cafes.create', compact('categories'));
    }

    public function storeCafe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'wifi' => 'nullable|boolean',
            'smoking_area' => 'nullable|boolean',
            'power_plugs' => 'nullable|integer',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'open_time' => 'required',
            'close_time' => 'required'
        ]);

        $cafe = \App\Models\Cafe::create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'address' => $request->address,
            'distance' => $request->distance,
            'approval_status' => 'approved',
            'status' => 'active',
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cafe_photos', 'public');
                $photoPaths[] = $path;
            }
        }

        \App\Models\CafeDetail::create([
            'cafe_id' => $cafe->id,
            'description' => $request->description,
            'wifi' => $request->has('wifi'),
            'smoking_area' => $request->has('smoking_area'),
            'power_plugs' => $request->power_plugs,
            'photos' => json_encode($photoPaths),
        ]);

        return redirect()->route('admin.cafes')->with('success', 'Cafe created successfully.');
    }

    public function editCafe(\App\Models\Cafe $cafe)
    {
        if (!$cafe->cafeDetail) {
            \App\Models\CafeDetail::create(['cafe_id' => $cafe->id]);
            $cafe->refresh();
        }
        $categories = \App\Models\Category::all();
        return view('admin.cafes.edit', compact('cafe', 'categories'));
    }

    public function updateCafe(Request $request, \App\Models\Cafe $cafe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'power_plugs' => 'nullable|integer',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'open_time' => 'nullable', // Should technically be required, but nullable for partial updates if needed? Let's say required if we want to enforce it. User said "tambahkan input", implying usage.
            'close_time' => 'nullable'
        ]);

        $cafe->update([
            'name' => $request->name,
            'address' => $request->address,
            'distance' => $request->distance,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time
        ]);

        $cafeDetail = $cafe->cafeDetail; // Ensure relationship exists from editCafe check or previous logic

        // Handle existing photos deletion
        $currentPhotos = json_decode($cafeDetail->photos ?? '[]', true);
        if ($request->has('remove_photos')) {
            foreach ($request->remove_photos as $photoToRemove) {
                if (($key = array_search($photoToRemove, $currentPhotos)) !== false) {
                    unset($currentPhotos[$key]);
                    // Optional: Delete file from storage if it's local
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photoToRemove)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($photoToRemove);
                    }
                }
            }
            $currentPhotos = array_values($currentPhotos); // Reindex
        }

        // Handle new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cafe_photos', 'public');
                $currentPhotos[] = $path;
            }
        }

        $cafeDetail->update([
            'description' => $request->description,
            'wifi' => $request->has('wifi'),
            'smoking_area' => $request->has('smoking_area'),
            'power_plugs' => $request->power_plugs,
            'photos' => json_encode($currentPhotos)
        ]);

        return redirect()->route('admin.cafes')->with('success', 'Cafe updated successfully');
    }

    public function deleteCafe(\App\Models\Cafe $cafe)
    {
        $cafe->delete();
        return redirect()->route('admin.cafes')->with('success', 'Cafe deleted successfully');
    }

    // --- Cafe Menus Management ---
    public function cafeMenus(\App\Models\Cafe $cafe)
    {
        $menus = $cafe->menus()->with('category')->paginate(10);
        $categories = \App\Models\Category::all(); // Global categories
        return view('admin.cafes.menus', compact('cafe', 'menus', 'categories'));
    }

    public function storeCafeMenu(Request $request, \App\Models\Cafe $cafe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image'
        ]);

        $data = $request->only(['name', 'price', 'category_id']);
        $data['cafe_id'] = $cafe->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        \App\Models\Menu::create($data);

        return back()->with('success', 'Menu added.');
    }

    public function deleteCafeMenu(\App\Models\Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu deleted.');
    }

    // --- Categories Management ---
    public function categories()
    {
        $categories = \App\Models\Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        \App\Models\Category::create(['name' => $request->name]);
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, \App\Models\Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update(['name' => $request->name]);
        return back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory(\App\Models\Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // --- Cafe Tables Management ---
    public function cafeTables(\App\Models\Cafe $cafe)
    {
        $tables = $cafe->tables;
        return view('admin.cafes.tables', compact('cafe', 'tables'));
    }

    public function storeCafeTable(Request $request, \App\Models\Cafe $cafe)
    {
        $request->validate(['name' => 'required', 'capacity' => 'required|integer']);
        $cafe->tables()->create($request->all());
        return back()->with('success', 'Table added.');
    }

    public function deleteCafeTable(\App\Models\Table $table)
    {
        $table->delete();
        return back()->with('success', 'Table deleted.');
    }
}

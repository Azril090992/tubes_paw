<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cart;
use App\Models\Table;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'cafe', 'table', 'items.menu'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cafe_id' => 'required|exists:cafes,id',
            'table_id' => 'required|exists:tables,id',
            'arrival_time' => 'required|date',
            'booking_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'deposit_amount' => 'required|numeric|min:50000',
            'voucher_code' => 'nullable|string',
        ]);

        // Check if table is available
        $table = Table::find($request->table_id);
        if ($table->status !== 'available') {
            return back()->with('error', 'Table is not available!');
        }

        // Check if table capacity is sufficient
        if ($table->capacity < $request->guest_count) {
            return back()->with('error', 'Table capacity is insufficient for the number of guests!');
        }

        // Set dynamic deposit amount
        $depositAmount = $request->deposit_amount;
        $totalAmount = $depositAmount;
        $voucherAmount = 0;
        $finalAmount = $totalAmount;

        // Apply voucher if provided
        if ($request->voucher_code) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->first();

            if ($voucher) {
                if ($voucher->min_purchase && $totalAmount < $voucher->min_purchase) {
                    return back()->with('error', 'Minimum purchase for this voucher is Rp ' . number_format($voucher->min_purchase));
                }

                if ($voucher->discount_type === 'percentage') {
                    $voucherAmount = $totalAmount * ($voucher->discount_value / 100);
                    if ($voucher->max_discount) {
                        $voucherAmount = min($voucherAmount, $voucher->max_discount);
                    }
                } else {
                    $voucherAmount = $voucher->discount_value;
                }

                $finalAmount = $totalAmount - $voucherAmount;
            } else {
                return back()->with('error', 'Invalid or expired voucher code!');
            }
        }

        DB::beginTransaction();
        try {
            // Check for double booking
            $exists = Booking::where('table_id', $request->table_id)
                ->whereDate('arrival_time', $request->arrival_time)
                ->whereTime('arrival_time', $request->booking_time)
                ->whereNotIn('status', ['cancelled', 'payment_rejected'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'This table is already booked for the selected date and time. Please choose another table or time.');
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'cafe_id' => $request->cafe_id,
                'table_id' => $request->table_id,
                'booking_code' => strtoupper(Str::random(8)),
                'booking_time' => now(),
                'arrival_time' => $request->arrival_time . ' ' . $request->booking_time,
                'people_count' => $request->guest_count,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'voucher_amount' => $voucherAmount,
                'final_amount' => $finalAmount,
                'deposit_amount' => $depositAmount,
            ]);

            // Note: We do NOT create booking items yet, nor delete cart. logic handles that later.

            DB::commit();

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking created! Please pay the deposit of Rp ' . number_format($finalAmount) . ' to confirm.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        // Make sure user can only view their own booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['cafe', 'table', 'items.menu']);
        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'cancelled' || $booking->status === 'completed') {
            return back()->with('error', 'Cannot cancel this booking!');
        }

        DB::beginTransaction();
        try {
            $booking->update(['status' => 'cancelled']);

            // Free up the table - REMOVED since we don't lock it permanently
            // $booking->table->update(['status' => 'available']);

            DB::commit();
            return back()->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking!');
        }
    }
    public function uploadPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $booking->update([
                'payment_proof' => $path,
                'status' => 'in_verification',
                'payment_status' => 'in_verification'
            ]);

            return back()->with('success', 'Payment proof uploaded successfully!');
        }

        return back()->with('error', 'Failed to upload payment proof.');
    }

    public function menu(Booking $booking, Request $request)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Logic: Menu locked on D-Day
        $arrivalDate = \Carbon\Carbon::parse($booking->arrival_time)->startOfDay();
        $isTodayOrPast = now()->startOfDay()->gte($arrivalDate);

        if ($isTodayOrPast) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Orders are locked on the day of arrival. Please contact the cafe directly.');
        }

        // Logic: Verified only
        if ($booking->payment_status !== 'paid' && $booking->payment_status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Please complete your deposit payment first.');
        }

        $cafe = $booking->cafe;

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

        $bookingItems = $booking->items()->with('menu')->get();

        // Recalculate totals dynamically
        $currentFoodTotal = $bookingItems->sum('subtotal');
        $deposit = $booking->deposit_amount;
        $remaining = max(0, $currentFoodTotal - $deposit);

        return view('bookings.menu', compact(
            'booking',
            'cafe',
            'menus',
            'menu_categories',
            'bookingItems',
            'currentFoodTotal',
            'deposit',
            'remaining'
        ));
    }

    public function updateItem(Booking $booking, Request $request)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Lock check
        $arrivalDate = \Carbon\Carbon::parse($booking->arrival_time)->startOfDay();
        if (now()->startOfDay()->gte($arrivalDate)) {
            return back()->with('error', 'Orders are locked.');
        }

        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'action' => 'required|in:add,remove',
        ]);

        $menu = \App\Models\Menu::findOrFail($request->menu_id);

        DB::beginTransaction();
        try {
            $item = BookingItem::where('booking_id', $booking->id)
                ->where('menu_id', $menu->id)
                ->first();

            if ($request->action === 'add') {
                if ($item) {
                    $item->qty += 1;
                    $item->subtotal = $item->qty * $menu->price;
                    $item->save();
                } else {
                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'menu_id' => $menu->id,
                        'qty' => 1,
                        'subtotal' => $menu->price
                    ]);
                }
            } else {
                if ($item) {
                    if ($item->qty > 1) {
                        $item->qty -= 1;
                        $item->subtotal = $item->qty * $menu->price;
                        $item->save();
                    } else {
                        $item->delete();
                    }
                }
            }

            // Update Booking Totals
            $totalFood = $booking->items()->sum('subtotal');
            $deposit = $booking->deposit_amount;

            $toPay = max(0, $totalFood - $deposit);

            $booking->update([
                'total_amount' => $totalFood,
                'final_amount' => $toPay,
            ]);

            DB::commit();
            return back()->with('success', 'Order updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function verifyOrder(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Order confirmed and saved successfully!');
    }

    public function export(Booking $booking)
    {
        // Allow Owner (User) OR Admin
        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $booking->load(['cafe', 'items.menu', 'table', 'user']);

        return view('bookings.export', compact('booking'));
    }
}
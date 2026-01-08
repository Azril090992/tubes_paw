<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Get all cart items for authenticated user
    public function index()
    {
        $cartItems = Cart::with('menu.cafe')
            ->forUser(auth()->id())
            ->get();

        $cartByCafe = $cartItems->groupBy('menu.cafe_id')->map(function ($items) {
            return [
                'cafe' => $items->first()->menu->cafe,
                'items' => $items,
                'subtotal' => $items->sum('subtotal')
            ];
        });

        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartByCafe', 'total'));
    }

    // Add item to cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $userId = auth()->id();
        
        // Check if menu is available
        $menu = Menu::find($request->menu_id);
        if (!$menu->is_available) {
            return back()->with('error', 'Menu is not available!');
        }

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', $userId)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($existingCart) {
            // Update quantity if already exists
            $existingCart->update([
                'quantity' => $existingCart->quantity + $request->quantity,
                'notes' => $request->notes ?? $existingCart->notes,
            ]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => $userId,
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ]);
        }

        return back()->with('success', 'Item added to cart successfully!');
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart->update([
            'quantity' => $request->quantity,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Cart updated successfully!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    // Clear all cart items for user
    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Cart cleared successfully!');
    }

    // Clear cart items for specific cafe
    public function clearCafe($cafeId)
    {
        Cart::forUser(auth()->id())
            ->forCafe($cafeId)
            ->delete();

        return back()->with('success', 'Cart items from this cafe cleared!');
    }

    // Get cart count (for navbar badge)
    public function getCount()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');
        
        return response()->json(['count' => $count]);
    }

    // Get cart total
    public function getTotal()
    {
        $total = Cart::with('menu')
            ->forUser(auth()->id())
            ->get()
            ->sum('subtotal');

        return response()->json(['total' => $total]);
    }
}
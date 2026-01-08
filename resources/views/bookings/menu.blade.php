@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Order for Booking #{{ $booking->booking_code }}</h1>
                <p class="text-gray-600">Arrival:
                    {{ \Carbon\Carbon::parse($booking->arrival_time)->format('D, d M Y H:i') }}</p>
            </div>
            <a href="{{ route('bookings.show', $booking) }}" class="text-amber-600 hover:text-amber-800 font-medium">
                &larr; Back to Booking
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Menu Section -->
            <div class="lg:w-2/3">
                <!-- Categories -->
                <div class="flex overflow-x-auto pb-4 gap-2 mb-6 scrollbar-hide">
                    <a href="{{ route('bookings.menu', $booking) }}"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition {{ !request('cat') ? 'bg-amber-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                        All
                    </a>
                    @foreach($menu_categories as $category)
                        <a href="{{ route('bookings.menu', [$booking, 'cat' => $category->id]) }}"
                            class="px-4 py-2 rounded-full whitespace-nowrap transition {{ request('cat') == $category->id ? 'bg-amber-800 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Menu Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($menus as $menu)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                class="h-48 w-full object-cover">
                            <div class="p-4 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $menu->name }}</h3>
                                    <span class="font-bold text-amber-800">Rp
                                        {{ number_format($menu->price, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-4 flex-1">{{ Str::limit($menu->description, 80) }}</p>

                                <div class="mt-auto">
                                    @if ($menu->is_available)
                                        <form action="{{ route('bookings.updateItem', $booking) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                            <input type="hidden" name="action" value="add">
                                            <button type="submit"
                                                class="w-full bg-amber-100 text-amber-800 py-2 rounded-lg hover:bg-amber-200 transition font-medium">
                                                Add to Order
                                            </button>
                                        </form>
                                    @else
                                        <button disabled
                                            class="w-full bg-gray-100 text-gray-400 py-2 rounded-lg cursor-not-allowed font-medium">
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary (Sidebar) -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-8">
                    <h3 class="font-bold text-xl mb-4 border-b pb-2">Your Order</h3>

                    @if($bookingItems->count() > 0)
                        <div class="space-y-4 mb-6 max-h-[60vh] overflow-y-auto">
                            @foreach($bookingItems as $item)
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $item->menu->name }}</h4>
                                        <div class="text-sm text-gray-500">Rp {{ number_format($item->menu->price, 0, ',', '.') }} x
                                            {{ $item->qty }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('bookings.updateItem', $booking) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $item->menu_id }}">
                                            <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </form>
                                        <span class="font-medium text-gray-900 w-4 text-center">{{ $item->qty }}</span>
                                        <form action="{{ route('bookings.updateItem', $booking) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $item->menu_id }}">
                                            <input type="hidden" name="action" value="add">
                                            <button type="submit" class="text-gray-400 hover:text-green-600 transition">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Food Total</span>
                                <span>Rp {{ number_format($currentFoodTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Paid Deposit / Credit</span>
                                <span>- Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                <span>Remaining Due</span>
                                <span class="{{ $remaining > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">*Remaining balance can be paid at the cashier.</p>

                            <div class="mt-6 border-t pt-4">
                                <form action="{{ route('bookings.verifyOrder', $booking) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium shadow-md">
                                        <i class="fas fa-check-circle mr-2"></i> Finish & Save Order
                                    </button>
                                    <p class="text-xs text-center text-gray-400 mt-2">Click to confirm items and return to
                                        booking details.</p>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-utensils text-4xl mb-3 opacity-20"></i>
                            <p>No items ordered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
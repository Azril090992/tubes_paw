<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Coffee & Co</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-stone-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-coffee text-2xl text-amber-800"></i>
                    <span class="text-2xl font-serif font-bold text-amber-900">BOCAF</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Message -->
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-check text-green-600 text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-amber-900 mb-2">Booking Confirmed!</h1>
                    <p class="text-gray-600">Your table has been reserved successfully</p>
                </div>

                <!-- Booking Details -->
                <div class="border-t border-b py-6 mb-6">
                    <h2 class="text-xl font-bold text-amber-900 mb-4">Booking Details</h2>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Booking ID</p>
                            <p class="font-bold text-amber-900">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span
                                class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-store text-amber-700 w-5"></i>
                            <div>
                                <p class="text-sm text-gray-600">Cafe</p>
                                <p class="font-medium text-gray-900">{{ $booking->cafe->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar text-amber-700 w-5"></i>
                            <div>
                                <p class="text-sm text-gray-600">Date & Time</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('l, d F Y - H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chair text-amber-700 w-5"></i>
                            <div>
                                <p class="text-sm text-gray-600">Table</p>
                                <p class="font-medium text-gray-900">
                                    {{ $booking->table->name }}
                                    ({{ $booking->table->capacity }} seats)
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users text-amber-700 w-5"></i>
                            <div>
                                <p class="text-sm text-gray-600">Guests</p>
                                <p class="font-medium text-gray-900">{{ $booking->people_count }}
                                    {{ $booking->people_count == 1 ? 'person' : 'people' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">Your Order</h3>
                    <div class="space-y-3">
                        @foreach($booking->items as $item)
                            <div class="flex items-center justify-between py-3 border-b">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('storage/' . $item->menu->image) }}"
                                        class="w-16 h-16 rounded-lg object-cover">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $item->qty }}x Rp
                                            {{ number_format($item->menu->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <p class="font-bold text-amber-900">Rp
                                    {{ number_format($item->qty * $item->menu->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Details -->
                <!-- Payment Details -->
                <div class="bg-amber-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">Payment Details</h3>
                    <div class="space-y-2">
                        @php
                            $orderTotal = $booking->items->sum('subtotal');
                            $deposit = $booking->deposit_amount;
                            $voucher = $booking->voucher_amount ?? 0;
                            $isDepositPaid = in_array($booking->payment_status, ['paid', 'confirmed']);
                        @endphp

                        @if($isDepositPaid)
                            <!-- Phase 2: Ordering (Deposit Paid) -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Subtotal (Food & Drink)</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($orderTotal, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-green-700">
                                <span class="text-gray-600">Paid Deposit</span>
                                <span class="font-medium">- Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                            </div>

                            <div class="border-t border-amber-200 my-2"></div>

                            <div class="flex justify-between text-lg font-bold text-amber-900">
                                <span>Remaining Bill</span>
                                <span>Rp {{ number_format(max(0, $orderTotal - $deposit), 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 italic">*Remaining balance to be paid at cashier.</p>
                        @else
                            <!-- Phase 1: Initial Booking (Deposit Payment) -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Deposit Required</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                            </div>

                            @if($voucher > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Voucher Discount</span>
                                    <span>- Rp {{ number_format($voucher, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="border-t border-amber-200 my-2"></div>

                            <div class="flex justify-between text-lg font-bold text-amber-900">
                                <span>Total to Pay (Deposit)</span>
                                <span>Rp {{ number_format(max(0, $deposit - $voucher), 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="mt-4 pt-2">
                            <span class="inline-block px-3 py-1 {{ $isDepositPaid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-sm font-medium">
                                <i class="fas {{ $isDepositPaid ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                Payment Status: {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions & Upload -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div class="w-full">
                            <h4 class="font-bold text-blue-900 mb-2">Payment Instructions</h4>
                            <p class="text-sm text-blue-800 mb-4">Please transfer the total amount to <strong>BCA
                                    1234567890 a.n. BOCAF</strong> and upload the proof below.</p>

                            @if($booking->payment_proof)
                                <div class="bg-white p-4 rounded-lg border border-blue-200">
                                    <p class="font-bold text-gray-700 mb-2">Payment Proof Uploaded:</p>
                                    <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Payment Proof"
                                        class="max-w-xs rounded-lg shadow-sm border">
                                    <p class="text-sm text-gray-500 mt-2">Status:
                                        {{ $booking->status == 'in_verification' ? 'Waiting for Verification' : ucfirst($booking->status) }}
                                    </p>
                                </div>
                            @elseif($booking->status == 'pending')
                                <form action="{{ route('bookings.payment', $booking->id) }}" method="POST"
                                    enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Transfer
                                            Screenshot</label>
                                        <input type="file" name="payment_proof" accept="image/*" required class="block w-full text-sm text-gray-500
                                                                                                                    file:mr-4 file:py-2 file:px-4
                                                                                                                    file:rounded-full file:border-0
                                                                                                                    file:text-sm file:font-semibold
                                                                                                                    file:bg-blue-50 file:text-blue-700
                                                                                                                    hover:file:bg-blue-100
                                                                                                                " />
                                    </div>
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                        Upload Proof
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-gray-900 mb-2">Important Notes:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Please arrive 10 minutes before your booking time</li>
                        <li>• Your table will be held for 15 minutes after booking time</li>
                        <li>• Contact the cafe if you need to cancel or reschedule</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex space-x-4">
                    <a href="/cafes/{{ $booking->cafe_id }}"
                        class="flex-1 bg-amber-800 text-white py-3 rounded-lg hover:bg-amber-900 transition text-center font-medium">
                        Back to Cafe
                    </a>
                    <button onclick="window.print()"
                        class="flex-1 border-2 border-amber-800 text-amber-800 py-3 rounded-lg hover:bg-amber-50 transition font-medium">
                        <i class="fas fa-print mr-2"></i>Print Confirmation
                    </button>
                </div>

                @if($booking->payment_status === 'paid' && !in_array($booking->status, ['cancelled', 'completed']))
                    <div class="mt-4">
                        @php
                            $arrivalDate = \Carbon\Carbon::parse($booking->arrival_time)->startOfDay();
                            $isLocked = now()->startOfDay()->gte($arrivalDate);
                        @endphp

                        @if(!$isLocked)
                            <a href="{{ route('bookings.menu', $booking) }}" 
                               class="flex items-center justify-center w-full bg-green-700 text-white py-3 rounded-lg hover:bg-green-800 transition font-medium shadow-md">
                                <i class="fas fa-utensils mr-2"></i> Manage Order
                            </a>
                            <p class="text-xs text-center text-gray-500 mt-2">
                                Adds/Edits are allowed until the day of arrival.
                            </p>
                        @else
                            <div class="bg-gray-100 p-4 rounded-lg text-center text-gray-500">
                                <i class="fas fa-lock mr-2"></i> Orders are locked (Arrival Day/Past)
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Cafe Contact -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-amber-900 mb-4">Need Help?</h3>
                <div class="space-y-3">
                    <a href="tel:+628123456789" class="flex items-center space-x-3 text-gray-600 hover:text-amber-800">
                        <i class="fas fa-phone text-amber-700"></i>
                        <span>Call {{ $booking->cafe->name }}</span>
                    </a>
                    <a href="mailto:info@cafe.com"
                        class="flex items-center space-x-3 text-gray-600 hover:text-amber-800">
                        <i class="fas fa-envelope text-amber-700"></i>
                        <span>Email Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
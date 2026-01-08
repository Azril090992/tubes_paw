@extends('admin.layouts.app')

@section('header', 'Manage Bookings')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row md:items-center justify-between gap-4">
            <!-- Filter Tabs (Status) -->
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                <a href="{{ route('admin.bookings', request()->except('status')) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition {{ !request('status') ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    All Status
                </a>
                <a href="{{ route('admin.bookings', array_merge(request()->all(), ['status' => 'in_verification'])) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition {{ request('status') == 'in_verification' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Verification
                </a>
                <a href="{{ route('admin.bookings', array_merge(request()->all(), ['status' => 'confirmed'])) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition {{ request('status') == 'confirmed' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Confirmed
                </a>
            </div>

            <!-- New Time Filters -->
            <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                
                <select name="filter_type" onchange="this.form.submit()" class="select select-bordered select-sm w-full max-w-xs focus:outline-none focus:border-gray-300 rounded-lg text-sm">
                    <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="schedule" {{ request('filter_type') == 'schedule' ? 'selected' : '' }}>Upcoming Schedule</option>
                    <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Specific Day</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Specific Month</option>
                    <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                </select>

                @if(request('filter_type') == 'day')
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" onchange="this.form.submit()" class="input input-bordered input-sm focus:outline-none focus:border-gray-300 rounded-lg text-sm">
                @endif

                @if(request('filter_type') == 'month')
                    <input type="month" name="filter_month" value="{{ request('filter_month') }}" onchange="this.form.submit()" class="input input-bordered input-sm focus:outline-none focus:border-gray-300 rounded-lg text-sm">
                @endif

                @if(request('filter_type') == 'range')
                    <div class="flex items-center gap-1">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="input input-bordered input-sm focus:outline-none focus:border-gray-300 rounded-lg text-sm" placeholder="Start">
                        <span class="text-gray-400">-</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="input input-bordered input-sm focus:outline-none focus:border-gray-300 rounded-lg text-sm" placeholder="End">
                    </div>
                @endif
            </form>

            <a href="{{ route('admin.bookings.export_all', request()->all()) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium text-sm">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Cafe & Table</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">#{{ $booking->booking_code }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->cafe->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->table->name ?? 'Table #' . $booking->table_id }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->arrival_time)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->arrival_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">Rp
                                    {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                @if($booking->deposit_amount > 0)
                                    <div class="text-xs text-green-600">Paid: Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }}</div>
                                @endif
                                @if($booking->final_amount < $booking->total_amount && $booking->final_amount > 0)
                                    <div class="text-xs text-red-600">Due: Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</div>
                                @endif
                                <div class="text-xs text-gray-500">
                                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'in_verification' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'payment_rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($booking->payment_proof && $booking->status == 'in_verification')
                                        <button onclick="openModal('{{ asset('storage/' . $booking->payment_proof) }}', {{ $booking->id }})"
                                            class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs hover:bg-blue-700 transition">
                                            Verify
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('bookings.export', $booking) }}" target="_blank"
                                        class="text-gray-600 hover:text-gray-900 px-2 text-lg" title="Export Struk">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    
                                    <button onclick="openBookingModal('{{ $booking->id }}')" 
                                        class="text-amber-600 hover:text-amber-800 px-2 text-lg" title="Quick View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                
                                <!-- Hidden Data for Modal -->
                                <div id="booking-data-{{ $booking->id }}" class="hidden">
                                     <div class="p-1">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="font-bold text-lg text-gray-900">{{ $booking->cafe->name }}</h3>
                                                <p class="text-sm text-gray-500">Booking #{{ $booking->booking_code }}</p>
                                            </div>
                                            <div class="text-right text-sm">
                                                <div class="font-medium">{{ $booking->user->name }}</div>
                                                <div class="text-gray-500">{{ $booking->user->email }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm bg-gray-50 p-3 rounded-lg">
                                            <div>
                                                <span class="block text-gray-500 text-xs">Arrival</span>
                                                <span class="font-medium">{{ \Carbon\Carbon::parse($booking->arrival_time)->format('D, d M Y H:i') }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 text-xs">Table</span>
                                                <span class="font-medium">{{ $booking->table->name }} ({{ $booking->people_count }} Pax)</span>
                                            </div>
                                        </div>

                                        <h4 class="font-bold text-sm border-b pb-1 mb-2 mt-4">Order Items</h4>
                                        @if($booking->items->count() > 0)
                                            <ul class="space-y-2 text-sm mb-4">
                                                @foreach($booking->items as $item)
                                                <li class="flex justify-between">
                                                    <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                                                    <span class="font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                            
                                            <div class="border-t pt-3 space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Order Subtotal:</span>
                                                    <span>{{ number_format($booking->items->sum('subtotal'), 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between text-green-600">
                                                    <span class="text-gray-600">Paid Deposit:</span>
                                                    <span>-{{ number_format($booking->deposit_amount, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between font-bold text-lg pt-2 border-t mt-2">
                                                    <span>Remaining Due:</span>
                                                    <span class="text-red-600">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 italic text-center py-4">No items ordered yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>

    <!-- DaisyUI Modal for Payment Verification -->
    <dialog id="verify_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Verify Payment</h3>
            <div class="flex justify-center mb-6">
                <img id="modalImage" src="" alt="Payment Proof" class="max-h-96 rounded-lg border border-gray-200">
            </div>
            
            <div class="modal-action flex justify-between">
                <form method="dialog">
                    <button class="btn btn-ghost">Cancel</button>
                </form>
                
                <div class="flex space-x-2">
                    <form id="rejectForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-error text-white">Reject</button>
                    </form>
                    <form id="verifyForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success text-white">Confirm Payment</button>
                    </form>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    
    <!-- Quick View Modal (Global) -->
    <dialog id="quick_view_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4 border-b pb-2">Booking Quick View</h3>
            <div id="quickViewContent"></div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function openModal(imageUrl, bookingId) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('verifyForm').action = `/admin/bookings/${bookingId}/confirm`;
            document.getElementById('rejectForm').action = `/admin/bookings/${bookingId}/reject`;
            document.getElementById('verify_modal').showModal();
        }
        
        function openBookingModal(id) {
            const content = document.getElementById('booking-data-' + id).innerHTML;
            document.getElementById('quickViewContent').innerHTML = content;
            document.getElementById('quick_view_modal').showModal();
        }
    </script>
@endsection

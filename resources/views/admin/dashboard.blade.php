@extends('admin.layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Bookings -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Bookings</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalBookings) }}</h3>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Registered Users</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers) }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Recent Bookings</h3>
            <a href="{{ route('admin.bookings') }}" class="text-sm text-amber-800 hover:underline font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium text-sm">
                    <tr>
                        <th class="px-6 py-4">Booking Code</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Cafe</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">#{{ $booking->booking_code }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->cafe->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'in_verification' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
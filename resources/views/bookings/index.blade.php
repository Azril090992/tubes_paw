@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2">My Bookings</h1>
            <p class="text-gray-600">Riwayat dan status pemesanan Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($bookings->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-12 text-center">
                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-2xl text-stone-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Belum ada booking</h3>
                <p class="text-gray-600 mb-6">Anda belum pernah melakukan booking meja.</p>
                <a href="{{ route('cafes.index') }}"
                    class="inline-block bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition">
                    Cari Cafe
                </a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach ($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden hover:shadow-md transition">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center space-x-2 text-sm text-amber-800 font-medium mb-1">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ \Carbon\Carbon::parse($booking->arrival_time)->translatedFormat('l, d F Y - H:i') }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $booking->cafe->name }}</h3>
                                    <p class="text-gray-500 text-sm">Kode Booking: <span
                                            class="font-mono font-medium text-gray-700">{{ $booking->booking_code }}</span></p>
                                </div>
                                <div class="flex flex-col items-end">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'in_verification' => 'bg-blue-100 text-blue-800',
                                        ];
                                        $statusClass = $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800';

                                        $statusLabels = [
                                            'pending' => 'Menunggu Pembayaran',
                                            'confirmed' => 'Terverifikasi',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            'rejected' => 'Ditolak',
                                            'in_verification' => 'Menunggu Verifikasi',
                                        ];
                                        $statusLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);

                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-2">
                                <div class="flex items-center space-x-6 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-chair w-4 text-center mr-2 text-gray-400"></i>
                                        <span>Meja {{ $booking->table->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users w-4 text-center mr-2 text-gray-400"></i>
                                        <span>{{ $booking->people_count }} Orang</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-receipt w-4 text-center mr-2 text-gray-400"></i>
                                        <span>
                                            Rp {{ number_format($booking->final_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <a href="{{ route('bookings.show', $booking) }}"
                                    class="text-amber-700 font-semibold hover:text-amber-800 hover:underline text-sm">
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
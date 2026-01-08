@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-amber-50 to-stone-100 py-20">
        <div class="container mx-auto px-6 max-w-4xl text-center">
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-amber-900 mb-6">
                Temukan pengalaman kopi terbaik di kota Anda
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Pesan meja Anda, pesan lebih awal, dan nikmati penawaran eksklusif
            </p>

            <!-- Search Bar -->
            <form action="{{ route('cafes.index') }}" method="GET"
                class="bg-white p-4 rounded-lg shadow-lg flex flex-col md:flex-row gap-4 max-w-4xl mx-auto">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Search for cafes or location..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-800">
                </div>
                <div class="md:w-48">
                    <select name="distance"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-800 text-gray-600">
                        <option value="">Any Distance</option>
                        <option value="1">Under 1 km</option>
                        <option value="3">Under 3 km</option>
                        <option value="5">Under 5 km</option>
                        <option value="10">Under 10 km</option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-amber-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-900 transition">
                    Search
                </button>
            </form>
        </div>
    </section>

    <!-- Featured Cafes -->
    <section id="cafes" class="py-16">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-4xl font-serif font-bold text-amber-900 mb-2">Featured Cafes</h2>
                    <p class="text-gray-600">Handpicked destinations for coffee lovers</p>
                </div>
                <a href="/cafes" class="text-amber-800 font-medium hover:text-amber-900">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($cafes as $cafe)
                    @php
                        $photos = json_decode($cafe->cafeDetail?->photos ?? '[]', true);
                        $amenities = [
                            ['wifi', 'fa-wifi', 'Free WiFi'],
                            ['power_plugs', 'fa-plug', 'Power Outlet'],
                            ['smoking_area', 'fa-smoking', 'Smoking Area'],
                        ];
                    @endphp

                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition group">
                        <div class="relative h-64 overflow-hidden">
                            @php
                                $photoUrl = $photos[0] ?? 'https://via.placeholder.com/400x300';
                                if (!Str::startsWith($photoUrl, 'http')) {
                                    $photoUrl = asset('storage/' . $photoUrl);
                                }
                            @endphp
                            <img src="{{ $photoUrl }}" alt="{{ $cafe->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div
                                class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-medium text-amber-800">
                                <i class="fas fa-star text-yellow-500"></i>
                                {{ number_format($cafe->averageRating(), 1) }}
                            </div>
                        </div>

                        <div class="p-6 ">
                            <h3 class="text-2xl font-serif font-bold text-amber-900 mb-2">{{ $cafe->name }}</h3>
                            <div class="mb-4 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-amber-700"></i>
                                <p class="text-gray-600">
                                    {{ Str::limit($cafe->address, 45) }}
                                </p>
                                <span class="flex items-center ml-auto text-sm text-gray-500">
                                    <i class="fas fa-location-dot text-amber-700 mr-1"></i>
                                    {{ number_format($cafe->distance, 1) ?? '-' }} km away
                                </span>
                            </div>

                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                @foreach ($amenities as [$field, $icon, $label])
                                    @if ($cafe->cafeDetail->$field ?? false)
                                        <span><i class="fas {{ $icon }} mr-1"></i> {{ $label }}</span>
                                    @endif
                                @endforeach
                            </div>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $cafe->cafeDetail->description ?? 'No description available' }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($cafe->open_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($cafe->close_time)->format('H:i') }}
                                </div>
                                <a href="/cafes/{{ $cafe->id }}"
                                    class="bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
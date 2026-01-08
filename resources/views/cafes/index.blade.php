@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-serif font-bold text-amber-900">All Cafes</h1>

            <!-- Search & Filter -->
            <form action="{{ route('cafes.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cafes..."
                        class="pl-10 pr-4 py-2 border rounded-full focus:ring-2 focus:ring-amber-500 outline-none">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button type="submit" class="bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition">
                    Search
                </button>
            </form>
        </div>

        @if($cafes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($cafes as $cafe)
                    @php
                        $photos = json_decode($cafe->cafeDetail->photos, true);
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
                            <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-medium text-amber-800">
                                <i class="fas fa-star text-yellow-500"></i>
                                {{ number_format($cafe->averageRating(), 1) }}
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-2xl font-serif font-bold text-amber-900 mb-2">{{ $cafe->name }}</h3>
                            <div class="mb-4">
                                <span class="flex items-center text-gray-600 mb-1">
                                    <i class="fas fa-map-marker-alt text-amber-700 mr-2"></i>
                                    {{ $cafe->distance ?? '-' }} km away
                                </span>
                                <p class="text-gray-600 line-clamp-1 pl-6">
                                    {{ $cafe->address }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                @foreach ($amenities as [$field, $icon, $label])
                                    @if ($cafe->cafeDetail->$field ?? false)
                                        <span><i class="fas {{ $icon }} mr-1"></i> {{ $label }}</span>
                                    @endif
                                @endforeach
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <a href="{{ route('cafes.show', $cafe->id) }}"
                                    class="w-full text-center bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $cafes->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-coffee text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-600">No cafes found</h3>
                <p class="text-gray-500 mt-2">Try adjusting your search terms.</p>
                <form action="{{ route('cafes.index') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <h3 class="font-bold text-gray-800 mb-4">Distance</h3>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="distance" value="" {{ request('distance') == '' ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-600">Any Distance</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="distance" value="1" {{ request('distance') == '1' ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-600">Less than 1 km</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="distance" value="3" {{ request('distance') == '3' ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-600">Less than 3 km</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="distance" value="5" {{ request('distance') == '5' ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-600">Less than 5 km</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="distance" value="10" {{ request('distance') == '10' ? 'checked' : '' }}
                                class="text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-600">Less than 10 km</span>
                        </label>
                    </div>
                    <button type="submit"
                        class="mt-4 w-full bg-amber-800 text-white py-2 rounded-lg text-sm hover:bg-amber-900 transition">Apply</button>
                </form>
                <a href="{{ route('cafes.index') }}" class="inline-block mt-4 text-amber-800 font-medium hover:underline">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
@endsection
```
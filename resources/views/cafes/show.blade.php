<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cafe->name }} - Coffee & Co</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @php
        $photos = json_decode($cafe->cafeDetail->photos ?? '[]', true);
    @endphp
</head>

<body class="bg-stone-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-coffee text-2xl text-amber-800"></i>
                    <!-- Open the modal using ID.showModal() method -->
                    <span class="text-2xl font-serif font-bold text-amber-900">BOCAF</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 hover:text-amber-800">Home</a>
                    <a href="#" class="text-amber-900 font-medium">Cafes</a>

                </div>

                <div class="flex items-center space-x-4">
                    <button onclick="toggleCart()" class="text-gray-600 hover:text-amber-800 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @if ($totalItems > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $totalItems }}</span>
                        @endif
                    </button>
                    @if (Auth::check())
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn m-1 btn-ghost">{{ Auth::user()->name }}</div>
                            <ul class="menu dropdown-content bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <button onclick="login.showModal()"
                            class="bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition">
                            Sign In
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Modal -->
    <dialog id="login" class="modal">
        <div class="modal-box max-w-md">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Header -->
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h3>
                    <p class="text-sm text-gray-600">Masuk ke akun Anda</p>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <!-- Email Field -->
                    <div>
                        <label for="login-email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email" id="login-email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="login-password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" name="password" id="login-password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="Masukkan password">
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                            <span class="ml-2 text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#" class="text-amber-700 hover:text-amber-800 font-medium">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-amber-800 text-white py-3 rounded-lg font-semibold hover:bg-amber-900 transform hover:scale-[1.02] transition duration-200 shadow-md hover:shadow-lg">
                    Masuk
                </button>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center text-sm">
                    <span class="text-gray-600">Belum punya akun?</span>
                    <button type="button" onclick="login.close(); register.showModal();"
                        class="text-amber-700 hover:text-amber-800 font-semibold ml-1">
                        Daftar sekarang
                    </button>
                </div>
            </form>

            <!-- Close Button -->
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
        </div>

        <!-- Modal Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Register Modal -->
    <dialog id="register" class="modal">
        <div class="modal-box max-w-md">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Header -->
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Daftar Akun</h3>
                    <p class="text-sm text-gray-600">Buat akun baru untuk memulai</p>
                </div>

                <!-- Form Fields -->
                <div class="space-y-4">
                    <!-- Name Field -->
                    <div>
                        <label for="register-name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" id="register-name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="register-email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email" id="register-email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="register-password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" name="password" id="register-password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="register-password-confirmation"
                            class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" id="register-password-confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-200 outline-none"
                            placeholder="Ulangi password">
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" required
                            class="w-4 h-4 mt-1 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            Saya menyetujui <a href="#" class="text-amber-700 hover:text-amber-800 font-medium">Syarat &
                                Ketentuan</a> dan <a href="#"
                                class="text-amber-700 hover:text-amber-800 font-medium">Kebijakan
                                Privasi</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-amber-800 text-white py-3 rounded-lg font-semibold hover:bg-amber-900 transform hover:scale-[1.02] transition duration-200 shadow-md hover:shadow-lg">
                    Daftar
                </button>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center text-sm">
                    <span class="text-gray-600">Sudah punya akun?</span>
                    <button type="button" onclick="register.close(); login.showModal();"
                        class="text-amber-700 hover:text-amber-800 font-semibold ml-1">
                        Masuk di sini
                    </button>
                </div>
            </form>

            <!-- Close Button -->
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
        </div>

        <!-- Modal Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>


    <!-- Cart Sidebar -->
    <div id="cartSidebar"
        class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-amber-900">Your Order</h3>
                <button onclick="toggleCart()" class="text-gray-600 hover:text-amber-800">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            @if ($totalItems > 0)
                <div class="space-y-4 mb-6">
                    @foreach ($cartItems as $item)
                        <div class="flex items-center space-x-4 pb-4 border-b">
                            <img src="{{ asset('storage/' . $item['menu']['image']) }}"
                                class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-amber-900">{{ $item['menu']['name'] }}</h4>
                                <p class="text-sm text-gray-600">Rp
                                    {{ number_format($item['menu']['price'], 0, ',', '.') }}
                                </p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST"
                                        class="flex items-center">
                                        @csrf
                                        @method('patch')
                                        <button type="button" onclick="updateQuantity(this, -1)"
                                            class="bg-gray-200 px-2 py-1 rounded">-</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            class="w-12 text-center border rounded mx-2" readonly>
                                        <button type="button" onclick="updateQuantity(this, 1)"
                                            class="bg-gray-200 px-2 py-1 rounded">+</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('delete')

                                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-bold">Total:</span>
                        <span class="font-bold text-amber-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <button onclick="scrollToBooking()"
                        class="w-full bg-amber-800 text-white py-3 rounded-lg hover:bg-amber-900 transition">
                        Proceed to Booking
                    </button>
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                        @csrf
                        @method('delete')
                        <button type="submit"
                            class="w-full border-2 border-amber-800 text-amber-800 py-2 rounded-lg hover:bg-amber-50 transition">
                            Clear Cart
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Your cart is empty</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="fixed top-20 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="/" class="hover:text-amber-800">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="/cafes" class="hover:text-amber-800">Cafes</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-amber-800 font-medium">{{ $cafe->name }}</span>
            </div>
        </div>
    </div>

    <!-- Hero Image Gallery -->
    <section class="bg-white">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-4 gap-4 h-96">
                <div class="col-span-2 row-span-2 overflow-hidden rounded-2xl">
                    @php
                        $mainPhoto = $photos[0] ?? 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800';
                        $mainPhotoUrl = Str::startsWith($mainPhoto, 'http') ? $mainPhoto : asset('storage/' . $mainPhoto);
                    @endphp
                    <img src="{{ $mainPhotoUrl }}" alt="Cafe Main"
                        class="w-full h-full object-cover hover:scale-105 transition duration-500 cursor-pointer">
                </div>
                @foreach ($photos as $index => $photo)
                    @if ($index > 0)
                        <div class="overflow-hidden rounded-2xl">
                            @php
                                $photoUrl = Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo);
                            @endphp
                            <img src="{{ $photoUrl }}" alt="Cafe Gallery"
                                class="w-full h-full object-cover hover:scale-105 transition duration-500 cursor-pointer">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="container mx-auto px-6">
            <!-- Single Column Stack -->
            <div class="space-y-8">
                <!-- Cafe Info -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-4xl font-serif font-bold text-amber-900 mb-2">{{ $cafe->name }}</h1>
                            <div class="flex items-center space-x-4 text-gray-600 mb-2">
                                <span class="flex items-center">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    <strong
                                        class="text-amber-900 text-lg">{{ number_format($cafe->averageRating(), 1) }}</strong>
                                    <span class="ml-1">({{ $cafe->reviews()->count() }} reviews)</span>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-amber-700 mr-2"></i>
                                    {{ $distance }} away
                                </span>
                            </div>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-location-dot mr-2"></i>
                                {{ $cafe->address }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="bg-amber-50 text-amber-800 p-3 rounded-full hover:bg-amber-100 transition">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="bg-amber-50 text-amber-800 p-3 rounded-full hover:bg-amber-100 transition">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mb-6">
                        @if ($isOpen)
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-circle text-green-500 text-xs mr-2"></i>Open Now
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-circle text-red-500 text-xs mr-2"></i>Closed
                            </span>
                        @endif
                        @if ($cafe->cafeDetail->wifi)
                            <span class="bg-amber-50 text-amber-800 px-4 py-2 rounded-full text-sm">
                                <i class="fas fa-wifi mr-2"></i>Free WiFi
                            </span>
                        @endif
                        @if ($cafe->cafeDetail->power_plugs > 0)
                            <span class="bg-amber-50 text-amber-800 px-4 py-2 rounded-full text-sm">
                                <i class="fas fa-plug mr-2"></i>Power Outlets
                            </span>
                        @endif
                        @if (!$cafe->cafeDetail->smoking_area)
                            <span class="bg-amber-50 text-amber-800 px-4 py-2 rounded-full text-sm">
                                <i class="fas fa-smoking-ban mr-2"></i>No Smoking
                            </span>
                        @endif
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-xl font-bold text-amber-900 mb-3">About This Cafe</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $cafe->cafeDetail->description }}
                        </p>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-xl font-bold text-amber-900 mb-4">Opening Hours</h3>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Monday - Friday</span>
                            <span class="font-medium text-amber-900">
                                {{ \Carbon\Carbon::parse($cafe->open_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($cafe->close_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Booking Section (Moved Here) -->
                <div id="bookingCard" class="bg-white rounded-2xl p-8 shadow-sm">
                    <h3 class="text-3xl font-serif font-bold text-amber-900 mb-6">Book a Table</h3>
                    @foreach ($errors->all() as $error)
                        <div class="text-red-500 mb-4">{{ $error }}</div>
                    @endforeach

                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ optional(auth()->user())->id ?? '' }}">
                        <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">

                        <!-- Hidden Inputs for Form Submission -->
                        <input type="hidden" name="arrival_time" id="arrival_time_input" required>
                        <input type="hidden" name="booking_time" id="booking_time_input" required>

                        <!-- Date Selection (Carousel) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                            <div class="relative">
                                <button type="button" id="prevDateBtn"
                                    class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white shadow-md rounded-full p-2 hidden hover:bg-gray-100">
                                    <i class="fas fa-chevron-left text-amber-800"></i>
                                </button>

                                <div class="overflow-hidden mx-4">
                                    <div id="dateContainer"
                                        class="flex space-x-3 transition-transform duration-300 ease-in-out">
                                        <!-- Dates injected by JS -->
                                    </div>
                                </div>

                                <button type="button" id="nextDateBtn"
                                    class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white shadow-md rounded-full p-2 hover:bg-gray-100">
                                    <i class="fas fa-chevron-right text-amber-800"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Time Selection (Grid) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Time</label>
                            <div id="timeContainer" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <p class="text-gray-500 text-sm col-span-full">Please select a date first.</p>
                                <!-- Time slots injected by JS -->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Number of People</label>
                                <input type="number" name="guest_count" id="guestCount" required min="1"
                                    oninput="filterTables()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                                    placeholder="Enter number of guests">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Table</label>
                                <input type="hidden" name="table_id" id="tableIdInput" required>

                                <div id="tableContainer"
                                    class="grid grid-cols-2 gap-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach ($cafe->tables as $table)
                                        <button type="button"
                                            class="table-card group relative p-4 rounded-xl border border-gray-200 bg-white hover:border-amber-500 hover:shadow-md transition-all duration-200 text-left"
                                            data-id="{{ $table->id }}" data-capacity="{{ $table->capacity }}"
                                            data-type="{{ $table->table_type }}" onclick="selectTable(this)">

                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-bold text-amber-900">{{ $table->name }}</span>
                                                <span
                                                    class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                    {{ $table->capacity }} Seats
                                                </span>
                                            </div>
                                            <div class="table-status text-xs font-bold text-green-500 flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i> Available
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                                <p id="noTablesMsg" class="text-red-500 text-sm mt-2 hidden">No tables available for
                                    these criteria.</p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Deposit (Min: Rp
                                    50.000)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="deposit_amount" id="deposit_amount" min="50000"
                                        value="50000" required
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-amber-500 focus:ring-amber-500 sm:text-sm py-3"
                                        placeholder="50000">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    *The deposit will be deducted from your final bill. You can pay more if you wish.
                                </p>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-amber-800 text-white py-4 rounded-lg hover:bg-amber-900 transition font-medium text-lg">
                            Book & Pay Deposit
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t space-y-3">
                        <h4 class="font-bold text-amber-900 mb-3">Contact Information</h4>
                        <a href="tel:+628123456789"
                            class="flex items-center space-x-3 text-gray-600 hover:text-amber-800">
                            <i class="fas fa-phone text-amber-700"></i>
                            <span>+62 812-3456-789</span>
                        </a>
                        <a href="mailto:info@artisancoffee.com"
                            class="flex items-center space-x-3 text-gray-600 hover:text-amber-800">
                            <i class="fas fa-envelope text-amber-700"></i>
                            <span>info<span>@</span>{{ strtolower(str_replace(' ', '', $cafe->name)) }}.com</span>
                        </a>
                    </div>
                </div>

                <!-- Menu Section -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <h2 class="text-3xl font-serif font-bold text-amber-900 mb-6">Our Menu</h2>

                    <div class="flex space-x-2 mb-6 overflow-x-auto">
                        <form action="/cafes/{{ $cafe->id }}" method="GET">
                            <button type="submit"
                                class="px-6 py-2 rounded-full font-medium whitespace-nowrap
                                    {{ request()->query('cat') ? 'bg-gray-100 text-gray-700' : 'bg-amber-800 text-white' }}">
                                All Items
                            </button>
                        </form>
                        @foreach ($menu_categories as $category)
                            <form action="/cafes/{{ $cafe->id }}" method="GET">
                                <input type="hidden" name="cat" value="{{ $category->id }}">
                                <button type="submit"
                                    class="px-6 py-2 rounded-full font-medium whitespace-nowrap
                                                                                                                                                                                            {{ request()->query('cat') == $category->id ? 'bg-amber-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $category->name }}
                                </button>
                            </form>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($menus as $menu)
                            <div class="flex space-x-4 pb-6 border-b">
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                    class="w-24 h-24 rounded-xl object-cover">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-bold text-amber-900 text-lg">{{ $menu->name }}</h4>
                                        <span class="text-amber-800 font-bold">Rp
                                            {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-2">{{ $menu->description }}</p>
                                    <div class="flex items-center justify-between">
                                        @if ($menu->is_available)
                                            <span class="text-green-600 text-sm font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Available
                                            </span>
                                            
                                            <div class="text-sm text-gray-500 italic">
                                                <i class="fas fa-info-circle mr-1"></i> Book a table to order
                                            </div>
                                        @else
                                            <span class="text-red-600 text-sm font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-3xl font-serif font-bold text-amber-900">Customer Reviews</h2>
                        <button onclick="openReviewModal()"
                            class="bg-amber-800 text-white px-6 py-2 rounded-full hover:bg-amber-900 transition">
                            Write a Review
                        </button>
                    </div>

                    <div class="bg-amber-50 rounded-xl p-6 mb-6">
                        <div class="flex items-center space-x-8">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-amber-900 mb-2">
                                    {{ number_format($averageRating, 1) }}
                                </div>
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star text-yellow-500"></i>
                                        @elseif ($i == ceil($averageRating))
                                            <i class="fas fa-star-half-alt text-yellow-500"></i>
                                        @else
                                            <i class="far fa-star text-yellow-500"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-gray-600 text-sm">{{ $totalReviews }} reviews</div>
                            </div>
                            <div class="flex-1 space-y-2">
                                @php $t = $totalReviews == 0 ? 1 : $totalReviews; @endphp
                                @foreach ([5, 4, 3, 2, 1] as $star)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm text-gray-600 w-8">{{ $star }}★</span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-amber-500 h-2 rounded-full"
                                                style="width: {{ ($ratingCounts[$star] / $t) * 100 }}%"></div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-600 w-12 text-right">{{ $ratingCounts[$star] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach ($reviews as $review)
                            <div class="border-b pb-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-12 h-12 bg-amber-800 text-white rounded-full flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-bold text-amber-900">{{ $review->user->name }}
                                                </h4>
                                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                    <div class="flex text-yellow-500">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <i class="fas fa-star text-xs"></i>
                                                            @else
                                                                <i class="far fa-star text-xs"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span>• {{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black/30 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-amber-900">Write a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-600 hover:text-amber-800">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden" required>
                            <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 hover:text-yellow-500"
                                onclick="setRating({{ $i }})">
                                <i class="far fa-star" id="starIcon{{ $i }}"></i>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea name="comment" rows="4" required minlength="10"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                        placeholder="Share your experience..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-amber-800 text-white py-3 rounded-lg hover:bg-amber-900 transition">
                    Submit Review
                </button>
            </form>
        </div>
    </div>

    <!-- Location Map -->


    <!-- Footer -->
    <footer class="bg-amber-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-coffee text-2xl"></i>
                        <span class="text-2xl font-serif font-bold">BOCAF</span>
                    </div>
                    <p class="text-amber-100">Your ultimate destination for discovering and booking the best cafes in
                        town.</p>
                </div>
            </div>
            <div class="border-t border-amber-800 pt-8 text-center text-amber-100">
                <p>&copy; 2024 Coffee & Co. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            sidebar.classList.toggle('translate-x-full');
        }

        function scrollToBooking() {
            toggleCart();
            document.getElementById('bookingCard').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function updateQuantity(button, change) {
            const form = button.closest('form');
            const input = form.querySelector('input[name="quantity"]');
            const newValue = parseInt(input.value) + change;
            if (newValue >= 1) {
                input.value = newValue;
                form.submit();
            }
        }

        const existingBookings = @json($existingBookings);
        const cafeOpenTime = "{{ $cafe->open_time }}";
        const cafeCloseTime = "{{ $cafe->close_time }}";
        const totalTables = {{ $cafe->tables->count() }}; // Define totalTables

        document.addEventListener('DOMContentLoaded', function () {
            initDateCarousel();
            document.getElementById('guestCount').addEventListener('input', filterTables);
        });

        // Helper to parse booking time string (handles "YYYY-MM-DD HH:MM:SS" or ISO)
        function parseBookingDateTime(dateTimeStr) {
            if (!dateTimeStr) return { date: '', time: '' };
            // Split by "T" (ISO) or " " (SQL)
            const parts = dateTimeStr.split(/[T ]/);
            const date = parts[0];
            // Take first 5 chars of time part (HH:MM)
            const time = parts[1] ? parts[1].substring(0, 5) : '';
            return { date, time };
        }

        // --- Date Carousel Logic ---
        let currentDateIndex = 0;
        const daysToShow = 10; // User requested 10 days pagination
        const totalDays = 60; // Render next 60 days total
        const dates = [];

        function initDateCarousel() {
            const container = document.getElementById('dateContainer');
            const prevBtn = document.getElementById('prevDateBtn');
            const nextBtn = document.getElementById('nextDateBtn');
            const today = new Date();

            // Generate dates
            for (let i = 0; i < totalDays; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                dates.push(date);
            }

            renderDates(container);

            // Button Event Listeners
            prevBtn.addEventListener('click', () => {
                if (currentDateIndex > 0) {
                    currentDateIndex -= daysToShow;
                    if (currentDateIndex < 0) currentDateIndex = 0;
                    updateCarouselView(container);
                    updateNavButtons(prevBtn, nextBtn);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentDateIndex + daysToShow < dates.length) {
                    currentDateIndex += daysToShow;
                    updateCarouselView(container);
                    updateNavButtons(prevBtn, nextBtn);
                }
            });

            updateNavButtons(prevBtn, nextBtn);
        }

        function renderDates(container) {
            container.innerHTML = '';
            dates.forEach((date, index) => {
                const dateCard = document.createElement('button');
                dateCard.type = 'button';
                dateCard.className = `flex-shrink-0 w-24 p-3 rounded-xl border border-gray-200 text-center hover:border-amber-500 hover:bg-amber-50 transition focus:outline-none date-card`;

                // Fix timezone issue: use local date string instead of ISO
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                dateCard.dataset.date = `${year}-${month}-${day}`;

                const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
                const dayNum = date.getDate();
                const monthInfo = date.toLocaleDateString('en-US', { month: 'short' });

                dateCard.innerHTML = `
            <div class="text-xs text-gray-500 font-medium">${monthInfo}</div>
            <div class="text-xl font-bold text-gray-800 my-1">${dayNum}</div>
            <div class="text-xs text-gray-500">${dayName}</div>
        `;

                dateCard.addEventListener('click', () => selectDate(dateCard));
                container.appendChild(dateCard);
            });
        }

        function updateCarouselView(container) {
            // Scroll calculation: items are w-24 + space-x-3 (approx 108px total width per item)
            // Or simplified: Just hide/show? No, improved sliding is better.
            // Simple approach: transform translate
            const itemWidth = 108; // 96px width + 12px gap
            const translateVal = -(currentDateIndex * itemWidth);
            container.style.transform = `translateX(${translateVal}px)`;
        }

        function updateNavButtons(prevBtn, nextBtn) {
            prevBtn.style.display = currentDateIndex === 0 ? 'none' : 'block';
            nextBtn.style.display = (currentDateIndex + daysToShow >= dates.length) ? 'none' : 'block';
        }

        function selectDate(cardElement) {
            // UI Update
            document.querySelectorAll('.date-card').forEach(el => {
                el.classList.remove('border-amber-600', 'bg-amber-100', 'ring-2', 'ring-amber-200');
                el.classList.add('border-gray-200');
            });
            cardElement.classList.remove('border-gray-200');
            cardElement.classList.add('border-amber-600', 'bg-amber-100', 'ring-2', 'ring-amber-200');

            // Set Value
            const selectedDate = cardElement.dataset.date;
            console.log('Selected Date:', selectedDate);
            console.log('Setting arrival_time_input to:', selectedDate);
            document.getElementById('arrival_time_input').value = selectedDate;

            // Trigger Time Slots
            renderTimeSlots(selectedDate);

            // Allow table filter to run if time is already picked
            document.getElementById('booking_time_input').value = ''; // Reset time on date change
            filterTables();
        }

        // --- Time Slot Logic ---
        function renderTimeSlots(dateString) {
            const container = document.getElementById('timeContainer');
            container.innerHTML = '';

            // Parse Open/Close times
            // cafeOpenTime is typical "HH:MM:SS"
            const [openH, openM] = cafeOpenTime.split(':').map(Number);
            const [closeH, closeM] = cafeCloseTime.split(':').map(Number);

            // Generate slots (1 hour intervals for simplicity, or 30 mins)
            // Let's assume 1 hour slots based on user image style
            let currentH = openH;

            // Safety measure: if close < open (e.g. late night), handle next day? Assuming same day for now.
            const endH = closeH;

            if (currentH >= endH) {
                container.innerHTML = '<p class="text-red-500 text-sm col-span-full">Closed</p>';
                return;
            }

            while (currentH < endH) {
                const startStr = `${String(currentH).padStart(2, '0')}:00`;
                const endStr = `${String(currentH + 1).padStart(2, '0')}:00`;

                // Check Availability using robust string parsing
                const bookingsForSlot = existingBookings.filter(booking => {
                    const { date, time } = parseBookingDateTime(booking.arrival_time);
                    return date === dateString && time === startStr;
                });

                const isFull = bookingsForSlot.length >= totalTables;

                // Construct button
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'time-slot-btn group relative w-full p-4 rounded-xl border border-gray-200 bg-white transition-all duration-200 flex flex-col items-center justify-center gap-2';

                if (!isFull) {
                    btn.classList.add('hover:border-amber-500', 'hover:shadow-md');
                    btn.onclick = () => selectTime(btn);
                } else {
                    btn.classList.add('opacity-50', 'bg-gray-50', 'cursor-not-allowed');
                    btn.disabled = true;
                }

                btn.dataset.time = startStr;

                const statusHtml = isFull
                    ? `<span class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full">Full</span>`
                    : `<span class="text-xs font-bold text-green-500 bg-green-50 px-3 py-1 rounded-full">Available</span>`;

                // Changed: Only show Start Time
                btn.innerHTML = `
                    <span class="text-sm font-bold text-gray-800">${startStr}</span>
                     ${statusHtml}
                `;

                container.appendChild(btn);

                currentH++;
            }
        }

        function selectTime(btnElement) {
            if (btnElement.disabled) return;

            // UI Update
            document.querySelectorAll('.time-slot-btn').forEach(el => {
                el.classList.remove('border-amber-600', 'bg-amber-100', 'ring-2', 'ring-amber-200');
                el.classList.add('border-gray-200');
            });
            btnElement.classList.remove('border-gray-200');
            btnElement.classList.add('border-amber-600', 'bg-amber-100', 'ring-2', 'ring-amber-200');

            // Set Value
            document.getElementById('booking_time_input').value = btnElement.dataset.time;

            // Trigger Filter
            filterTables();
        }

        function selectTable(cardElement) {
            if (cardElement.disabled || cardElement.classList.contains('opacity-50')) return;

            // UI Update
            document.querySelectorAll('.table-card').forEach(el => {
                el.classList.remove('border-amber-600', 'bg-amber-50', 'ring-2', 'ring-amber-200');
                el.classList.add('border-gray-200', 'bg-white');
            });

            cardElement.classList.remove('border-gray-200', 'bg-white');
            cardElement.classList.add('border-amber-600', 'bg-amber-50', 'ring-2', 'ring-amber-200');

            // Set Value
            document.getElementById('tableIdInput').value = cardElement.dataset.id;
        }

        function filterTables() {
            const dateInput = document.getElementById('arrival_time_input');
            const timeInput = document.getElementById('booking_time_input');
            const guestCountSelect = document.getElementById('guestCount');
            const tableCards = document.querySelectorAll('.table-card');
            const tableInput = document.getElementById('tableIdInput');

            const selectedDate = dateInput.value;
            const selectedTime = timeInput.value;
            const guestCount = parseInt(guestCountSelect.value) || 0;

            if (!selectedDate || !selectedTime) {
                tableCards.forEach(card => {
                    card.style.display = 'block';
                    card.classList.remove('bg-white', 'hover:border-amber-500', 'hover:shadow-md');
                    card.classList.add('opacity-60', 'cursor-not-allowed', 'bg-gray-100');
                    card.disabled = true;
                    card.onclick = null;
                    card.querySelector('.table-status').innerHTML = '';
                });
                return;
            }

            let visibleCount = 0;

            tableCards.forEach(card => {
                const tableCapacity = parseInt(card.dataset.capacity);
                const tableId = parseInt(card.dataset.id);

                let isBooked = existingBookings.some(booking => {
                    const { date, time } = parseBookingDateTime(booking.arrival_time);
                    const sTime = selectedTime.substring(0, 5);
                    console.log(booking.table_id, tableId, date, selectedDate, time, sTime);
                    return booking.table_id === tableId && date === selectedDate && time === sTime;
                });

                const statusDiv = card.querySelector('.table-status');

                if (guestCount > 0 && tableCapacity < guestCount) {
                    // Hide or disable
                    card.style.display = 'none';
                } else if (isBooked) {
                    card.style.display = 'block'; // Show but disabled/booked style
                    card.classList.remove('bg-white', 'hover:border-amber-500', 'hover:shadow-md');
                    card.classList.add('opacity-60', 'cursor-not-allowed', 'bg-gray-100');
                    card.disabled = true;
                    card.onclick = null; // Remove click handler

                    if (tableInput.value == tableId) {
                        tableInput.value = '';
                        card.classList.remove('border-amber-600', 'bg-amber-50', 'ring-2', 'ring-amber-200');
                        card.classList.add('border-gray-200');
                    }

                    statusDiv.className = 'table-status text-xs font-bold text-red-500 flex items-center shadow-sm bg-white px-2 py-1 rounded-full border border-red-100';
                    statusDiv.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Booked';
                } else {
                    card.style.display = 'block';
                    card.classList.remove('opacity-60', 'cursor-not-allowed', 'bg-gray-100');
                    card.classList.add('bg-white', 'hover:border-amber-500', 'hover:shadow-md');
                    card.disabled = false;
                    card.onclick = function () { selectTable(this); }; // Restore click

                    statusDiv.className = 'table-status text-xs font-bold text-green-500 flex items-center shadow-sm bg-white px-2 py-1 rounded-full border border-green-100';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Available';
                    visibleCount++;
                }
            });

            // Reselect logic: if currently selected is now hidden/disabled, clear selection
            const currentSelectedId = tableInput.value;
            if (currentSelectedId) {
                const currentCard = document.querySelector(`.table-card[data-id="${currentSelectedId}"]`);
                if (currentCard && (currentCard.style.display === 'none' || currentCard.disabled)) {
                    tableInput.value = '';
                    currentCard.classList.remove('border-amber-600', 'bg-amber-50', 'ring-2', 'ring-amber-200');
                    currentCard.classList.add('border-gray-200', 'bg-white');
                }
            }

            const noMsg = document.getElementById('noTablesMsg');
            if (visibleCount === 0) {
                noMsg.classList.remove('hidden');
                noMsg.textContent = "No tables available for selected time or capacity.";
            } else {
                noMsg.classList.add('hidden');
            }
        }

        function openReviewModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
        }

        function setRating(rating) {
            for (let i = 1; i <= 5; i++) {
                const icon = document.getElementById('starIcon' + i);
                if (i <= rating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.classList.add('text-yellow-500');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.classList.remove('text-yellow-500');
                }
            }
            document.getElementById('star' + rating).checked = true;
        }

        // Auto-hide alerts after 3 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.fixed.top-20');
            alerts.forEach(alert => alert.style.display = 'none');
        }, 3000);
    </script>
</body>

</html>
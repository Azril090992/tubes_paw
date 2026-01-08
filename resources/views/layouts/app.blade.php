<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-stone-50 flex flex-col min-h-screen">
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
                    <a href="/cafes" class="text-amber-900 font-medium">Cafes</a>

                </div>

                <div class="flex items-center space-x-4">
                    @if (Auth::check())
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn m-1 btn-ghost">{{ Auth::user()->name }}</div>
                            <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                @if (Auth::user()->role === 'admin')
                                    <li><a href="/admin">Dashboard</a></li>
                                @else
                                    <li><a href="{{ route('bookings.index') }}">My Bookings</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log
                                        Out</a></li>
                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
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

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-amber-900 text-amber-100 py-8 text-center text-sm mt-auto">
        <p>© 2024 BOCAF</p>
    </footer>
</body>

</html>
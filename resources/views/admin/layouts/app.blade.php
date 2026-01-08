<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BOCAF</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-amber-900 text-white flex flex-col">
            <div class="p-6">
                <div class="flex items-center space-x-2 text-white">
                    <i class="fas fa-coffee text-2xl"></i>
                    <span class="text-2xl font-serif font-bold">BOCAF Admin</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-amber-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-800' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.bookings') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-amber-800 transition {{ request()->routeIs('admin.bookings') ? 'bg-amber-800' : '' }}">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Bookings</span>
                </a>
                <a href="{{ route('admin.cafes') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-amber-800 transition {{ request()->routeIs('admin.cafes') ? 'bg-amber-800' : '' }}">
                    <i class="fas fa-store w-5"></i>
                    <span>Cafes</span>
                </a>
                <a href="{{ route('admin.users') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-amber-800 transition {{ request()->routeIs('admin.users') ? 'bg-amber-800' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-amber-800 transition {{ request()->routeIs('admin.categories') ? 'bg-amber-800' : '' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span>Categories</span>
                </a>
            </nav>

            <div class="p-4 border-t border-amber-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-3 px-4 py-2 w-full text-amber-200 hover:text-white transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <header class="bg-white shadow-sm p-4 sticky top-0 z-10">
                <div class="flex justify-between items-center container mx-auto px-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('header', 'Dashboard')
                    </h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                        <div
                            class="h-8 w-8 rounded-full bg-amber-800 text-white flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="container mx-auto px-6 py-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                        role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
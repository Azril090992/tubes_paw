@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-stone-50">
        <div class="max-w-4xl w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Become a BOCAF Partner</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Register your cafe and reach more customers.
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('register.partner.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User Account Info -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-2">Owner Information</h3>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input id="name" name="name" type="text" required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="Your Name">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input id="email" name="email" type="email" required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="you@example.com">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input id="password" name="password" type="password" required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="Create a password">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="Repeat password">
                        </div>
                    </div>

                    <!-- Cafe Info -->
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-2">Cafe Information</h3>

                        <div>
                            <label for="cafe_name" class="block text-sm font-medium text-gray-700">Cafe Name</label>
                            <input id="cafe_name" name="cafe_name" type="text" required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="Cafe Name">
                        </div>

                        <div>
                            <label for="cafe_address" class="block text-sm font-medium text-gray-700">Cafe Address</label>
                            <textarea id="cafe_address" name="cafe_address" required rows="3"
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm"
                                placeholder="Full Address"></textarea>
                        </div>

                        <!-- Hidden fields for basic defaults or required logic -->
                        <input type="hidden" name="role" value="cafe_owner">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-amber-800 hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-store text-amber-500 group-hover:text-amber-400"></i>
                        </span>
                        Register Partner
                    </button>
                </div>

                <div class="text-center text-sm">
                    <a href="/" class="font-medium text-amber-600 hover:text-amber-500">
                        Already have an account? Sign in
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
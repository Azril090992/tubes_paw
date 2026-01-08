@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-3xl font-medium text-gray-700">Menus: {{ $cafe->name }}</h3>
                <a href="{{ route('admin.cafes') }}" class="text-sm text-blue-500 hover:underline">&larr; Back to Cafes</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Add Menu Form -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold mb-4">Add Menu Item</h4>
                    <form action="{{ route('admin.cafes.menus.store', $cafe->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="price" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Add
                            Item</button>
                    </form>
                </div>
            </div>

            <!-- Menu List -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($menus as $menu)
                                <tr>
                                    <td class="px-6 py-4">
                                        @if($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}"
                                                class="h-10 w-10 object-cover rounded">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded"></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $menu->name }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($menu->price) }}</td>
                                    <td class="px-6 py-4">{{ $menu->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.cafes.menus.delete', $menu->id) }}" method="POST"
                                            onsubmit="return confirm('Delete?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $menus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
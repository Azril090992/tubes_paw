@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-3xl font-medium text-gray-700">Tables: {{ $cafe->name }}</h3>
                <a href="{{ route('admin.cafes') }}" class="text-sm text-blue-500 hover:underline">&larr; Back to Cafes</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Add Table Form -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold mb-4">Add Table</h4>
                    <form action="{{ route('admin.cafes.tables.store', $cafe->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Name/Number</label>
                            <input type="text" name="name" required placeholder="e.g. Table 1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Capacity</label>
                            <input type="number" name="capacity" required placeholder="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 border p-2">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Add
                            Table</button>
                    </form>
                </div>
            </div>

            <!-- Table List -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Capacity</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tables as $table)
                                <tr>
                                    <td class="px-6 py-4">{{ $table->name }}</td>
                                    <td class="px-6 py-4">{{ $table->capacity }} Persons</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.cafes.tables.delete', $table->id) }}" method="POST"
                                            onsubmit="return confirm('Delete?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
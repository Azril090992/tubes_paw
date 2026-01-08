@extends('admin.layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Manage Categories</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="New Category Name" required
                    class="border rounded-md px-3 py-2 text-sm">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">Add</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                                    class="flex items-center">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}"
                                        class="border-transparent bg-transparent hover:border-gray-300 focus:border-blue-500 px-2 py-1 rounded">
                                    <button type="submit" class="ml-2 text-xs text-blue-600 hover:text-blue-900">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Delete category?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
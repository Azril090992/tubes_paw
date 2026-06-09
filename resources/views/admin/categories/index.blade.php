```php
@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">
            Manage Categories
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-2">
            @csrf

            <input type="text"
                   name="name"
                   placeholder="New Category Name"
                   required
                   class="border rounded-md px-3 py-2 text-sm">

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                Add
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Name
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach($categories as $category)

                <tr>
                    <td class="px-6 py-4">
                        {{ $category->name }}
                    </td>

                    <td class="px-6 py-4">

                        <button type="button"
                                onclick="openModal({{ $category->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-4">
                            Update
                        </button>

                        <form action="{{ route('admin.categories.delete', $category->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Delete category?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-red-600 hover:text-red-900">
                                Delete
                            </button>

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


{{-- MODAL --}}
@foreach($categories as $category)

<div id="modal-{{ $category->id }}"
     class="hidden fixed inset-0 z-50 flex items-center justify-center">

    <!-- Background -->
    <div class="absolute inset-0 bg-gray-900 opacity-30"></div>

    <!-- Modal Box -->
    <div class="relative bg-white rounded-xl shadow-xl w-[400px] p-6">

        <h2 class="text-lg font-semibold mb-4">
            Update Category
        </h2>

        <form action="{{ route('admin.categories.update', $category->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <input type="text"
                   name="name"
                   value="{{ $category->name }}"
                   required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-5">

            <div class="flex justify-end gap-2">

                <button type="button"
                        onclick="closeModal({{ $category->id }})"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Save
                </button>

            </div>

        </form>

    </div>

</div>

@endforeach


<script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
</script>

@endsection
```

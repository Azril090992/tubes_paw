@extends('admin.layouts.app')

@section('header', 'Manage Cafes')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 flex justify-between items-center border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">All Cafes</h3>
            <a href="{{ route('admin.cafes.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Cafe
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium text-sm">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Address</th>
                        <th class="px-6 py-4">Owner</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cafes as $cafe)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $cafe->id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cafe->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($cafe->address, 50) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $cafe->owner->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                    {{ ($cafe->approval_status ?? 'approved') === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                                    {{ ($cafe->approval_status ?? 'approved') === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ ($cafe->approval_status ?? 'approved') === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($cafe->approval_status ?? 'approved') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex flex-col space-y-1">
                                <div class="flex space-x-2 mb-1">
                                    <a href="{{ route('admin.cafes.menus', $cafe->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-xs font-medium border border-indigo-200 px-2 py-1 rounded">Menus</a>
                                    <a href="{{ route('admin.cafes.tables', $cafe->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-xs font-medium border border-indigo-200 px-2 py-1 rounded">Tables</a>
                                </div>
                                <div class="flex space-x-2 items-center">
                                    <a href="{{ route('admin.cafes.edit', $cafe->id) }}"
                                        class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                    <form action="{{ route('admin.cafes.delete', $cafe->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-100">
            {{ $cafes->links() }}
        </div>
    </div>
@endsection
@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-3xl font-medium text-gray-700">Create New Cafe</h3>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('admin.cafes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Cafe Name
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" type="text" name="name" required placeholder="Cafe Name">

                    <div class="mb-4">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                                Address
                            </label>
                            <textarea
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="address" name="address" required rows="3" placeholder="Full Address"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Distance (km)</label>
                            <input type="number" step="0.1" name="distance" min="0" placeholder="e.g. 1.5"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="wifi" value="1"
                                        class="form-checkbox h-5 w-5 text-blue-600">
                                    <span class="ml-2 text-gray-700">Has WiFi</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="smoking_area" value="1"
                                        class="form-checkbox h-5 w-5 text-blue-600">
                                    <span class="ml-2 text-gray-700">Smoking Area</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Power Plugs</label>
                            <input type="number" name="power_plugs" min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Open Time</label>
                                <input type="time" name="open_time" required step="3600"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Close Time</label>
                                <input type="time" name="close_time" required step="3600"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Cafe Photos (Select multiple)</label>
                            <input type="file" name="photos[]" multiple
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple files.
                            </p>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.cafes') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
                            <button
                                class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline"
                                type="submit">
                                Create Cafe
                            </button>
                        </div>
            </form>
        </div>
    </div>
@endsection
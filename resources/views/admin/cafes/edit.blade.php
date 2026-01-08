@extends('admin.layouts.app')

@section('header', 'Edit Cafe')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form action="{{ route('admin.cafes.update', $cafe->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Cafe Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $cafe->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">{{ old('address', $cafe->address) }}</textarea>
                </div>

                <div>
                    <label for="distance" class="block text-sm font-medium text-gray-700">Distance (km)</label>
                    <input type="number" step="0.1" name="distance" id="distance" value="{{ old('distance', $cafe->distance) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">
                </div>

                <div>
                     <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                     <textarea name="description" id="description" rows="4"
                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">{{ old('description', $cafe->cafeDetail->description ?? '') }}</textarea>
                </div>
 
                <div class="grid grid-cols-2 gap-4">
                     <div class="flex items-center">
                         <input type="checkbox" name="wifi" id="wifi" value="1" {{ ($cafe->cafeDetail->wifi ?? false) ? 'checked' : '' }} 
                             class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                         <label for="wifi" class="ml-2 block text-sm text-gray-900">Has WiFi</label>
                     </div>
                     <div class="flex items-center">
                         <input type="checkbox" name="smoking_area" id="smoking_area" value="1" {{ ($cafe->cafeDetail->smoking_area ?? false) ? 'checked' : '' }}
                             class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                         <label for="smoking_area" class="ml-2 block text-sm text-gray-900">Smoking Area</label>
                     </div>
                </div>
 
                <div>
                      <label for="power_plugs" class="block text-sm font-medium text-gray-700">Power Plugs </label>
                      <input type="number" name="power_plugs" id="power_plugs" min="0" value="{{ old('power_plugs', $cafe->cafeDetail->power_plugs ?? 0) }}"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">
                </div>

                <!-- Photos Management -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Photos</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @php
                            $photos = json_decode($cafe->cafeDetail->photos ?? '[]', true);
                        @endphp
                        @foreach($photos as $photo)
                            <div class="relative group">
                                <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}" class="h-24 w-full object-cover rounded-lg">
                                <label class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer rounded-lg">
                                    <input type="checkbox" name="remove_photos[]" value="{{ $photo }}" class="mr-2">
                                    <span class="text-white text-xs">Delete</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <label class="block text-sm font-medium text-gray-700">Add New Photos (Select multiple)</label>
                    <input type="file" name="photos[]" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple files.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="open_time" class="block text-sm font-medium text-gray-700">Open Time</label>
                        <input type="time" name="open_time" id="open_time" step="3600" value="{{ old('open_time', $cafe->open_time) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">
                    </div>
                    <div>
                        <label for="close_time" class="block text-sm font-medium text-gray-700">Close Time</label>
                        <input type="time" name="close_time" id="close_time" step="3600" value="{{ old('close_time', $cafe->close_time) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2 border">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.cafes') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-900 transition text-sm font-medium">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
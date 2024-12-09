<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Columbary Slot') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl container mx-auto p-6">

        <form action="{{ route('columbary.update', $slot->id) }}" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="slot_number">
                    Slot Number
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="slot_number" name="slot_number" type="text" value="{{ $slot->slot_number }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="floor_number">
                    Floor Number
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="floor_number" name="floor_number" type="number" value="{{ $slot->floor_number }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Price
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="price" name="price" type="number" step="0.01" value="{{ $slot->price }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="status" name="status" required>
                    <option value="Available" {{ $slot->status === 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Reserved" {{ $slot->status === 'Reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="Sold" {{ $slot->status === 'Sold' ? 'selected' : '' }}>Sold</option>
                    <option value="Not Available" {{ $slot->status === 'Not Available' ? 'selected' : '' }}>Not
                        Available</option>
                </select>
            </div>

            @if ($slot->payment)
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Payment Information</h3>
                    <p><strong>Buyer Name:</strong> {{ $slot->payment->buyer_name }}</p>
                    <p><strong>Contact Info:</strong> {{ $slot->payment->contact_info }}</p>
                    <p><strong>Payment Status:</strong> {{ $slot->payment->payment_status }}</p>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Update Slot
                </button>
                <a href="{{ route('columbary.list') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

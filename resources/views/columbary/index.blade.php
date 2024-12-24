<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Columbary Slots') }}
            </h2>
            <div class="btns flex items-center gap-4">
                <div>
                    <button onclick="openAddSlotsModal()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Slots
                    </button>
                </div>

                <form action="{{ route('columbary.loadIndex') }}" method="GET">
                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Load All Data
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl container mx-auto p-4">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @foreach ($floors as $floor)
                <div class="mb-6 text-black dark:text-white">
                    <button
                        class="w-full flex justify-between items-center bg-blue-500 text-white px-4 py-3 rounded-lg shadow focus:outline-none"
                        onclick="toggleCollapse('floor-{{ $floor }}')">
                        <span>Floor {{ $floor }}</span>
                        <span>&#9660;</span>
                    </button>

                    <div id="floor-{{ $floor }}" class="hidden mt-2">
                        @foreach ($slots[$floor] as $vault => $vaultSlots)
                            <div class="mb-4">
                                <button
                                    class="w-full flex justify-between items-center bg-green-500 text-white px-4 py-2 rounded-lg shadow focus:outline-none"
                                    onclick="toggleCollapse('vault-{{ $floor }}-{{ $vault }}')">
                                    <span>Rack {{ chr(64 + $vault) }}</span>
                                    <span>&#9660;</span>
                                </button>

                                <div id="vault-{{ $floor }}-{{ $vault }}" class="hidden mt-2">

                                    <div class="overflow-x-auto">
                                        <table class="table-auto w-full border-collapse border border-gray-300">
                                            <thead>
                                                <tr class="bg-gray-300 dark:bg-gray-700">
                                                    <th class="border border-gray-300 px-4 py-2 text-center">Slot Number
                                                    </th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Type</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Level</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Buyer Name
                                                    </th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Payment
                                                        Status</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vaultSlots as $slot)
                                                    <tr class="text-black dark:text-white hover:bg-gray-400">
                                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                                            {{ $slot->slot_number }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ $slot->type }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ $slot->level_number }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            @if ($slot)
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-white 
                                                            {{ $slot->status === 'Available' ? 'bg-green-500' : ($slot->status === 'Reserved' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                                                    {{ $slot->status }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-white bg-gray-500">
                                                                    Not Created
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            @if ($slot)
                                                                ₱{{ number_format($slot->price, 2) }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            @if ($slot && $slot->payment)
                                                                {{ $slot->payment->buyer_name }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            @if ($slot && $slot->payment)
                                                                {{ $slot->payment->payment_status }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>

                                                        <style>
                                                            .action-btns {
                                                                padding: 0.5rem 1rem;
                                                                margin-right: 4px;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 0.25rem;
                                                                cursor: pointer;
                                                                font-size: 0.750rem;
                                                            }
                                                        </style>
                                                        <td class="border border-gray-300 px-1 py-2 flex">
                                                            <a href="{{ route('columbary.edit', $slot->id) }}"
                                                                class="action-btns bg-blue-500">
                                                                Edit
                                                            </a>

                                                            @if (
                                                                !in_array($slot->status, ['Not Available', 'Reserved']) &&
                                                                    (($slot->payment && $slot->payment->payment_status !== 'Paid') || $slot->status !== 'Sold'))
                                                                <form
                                                                    action="{{ route('columbary.markNotAvailable', $slot->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="action-btns bg-red-500"
                                                                        onclick="return confirm('Make this slot Not Available?')">
                                                                        Not Available
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if (
                                                                in_array($slot->status, ['Not Available', 'Reserved']) &&
                                                                    (($slot->payment && $slot->payment->payment_status !== 'Paid') || $slot->status !== 'Sold'))
                                                                <form
                                                                    action="{{ route('columbary.makeAvailable', $slot->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit"
                                                                        class="action-btns bg-green-500"
                                                                        onclick="return confirm('Make this slot Available?')">
                                                                        Available
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if ($slot && $slot->status === 'Available')
                                                                @elseif($slot && $slot->status === 'Reserved')
                                                                    <button onclick="openModal({{ $slot->id }})"
                                                                        class="action-btns bg-green-500">
                                                                        View
                                                                    </button>

                                                                @if ($slot->payment)
                                                                    <form
                                                                        action="{{ route('columbary.paid', $slot->payment->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="action-btns bg-yellow-500"
                                                                            onclick="return confirm('Mark this slot as paid?')">Mark as Paid
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                {{-- <form
                                                                    action="{{ route('columbary.makeAvailable', $slot->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button
                                                                        class="action-btns bg-green-500"
                                                                        type="submit">
                                                                        Open
                                                                    </button>
                                                                </form> --}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div id="addSlotsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Slots</h3>
                <div class="mt-2">
                    <form id="addSlotsForm" method="POST" action="{{ route('columbary.create-slots') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="floor" class="block text-gray-700 text-sm font-bold mb-2">Floor:</label>
                            <input type="number" id="floor" name="floor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="rackSpecs" class="block text-gray-700 text-sm font-bold mb-2">Rack Specs (e.g., 1:84,2:144):</label>
                            <input type="text" id="rackSpecs" name="rackSpecs" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Slots</button>
                            <button type="button" onclick="closeAddSlotsModal()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="client-info-modal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden z-50 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg w-3/4 md:w-1/2 p-6">
            <h2 class="text-xl font-bold mb-4">Client Information</h2>
            <div id="modal-content" class="space-y-4">

            </div>

            <button onclick="closeModal()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mt-4">
                Close
            </button>
        </div>
    </div>
    </div>

    <script>
        function openModal(slotId) {
            fetch(`/columbary/slot-info/${slotId}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const modalContent = document.getElementById('modal-content');
                    modalContent.innerHTML = `
            <p><strong>id:</strong> ${data.id}</p>
                <p><strong>Buyer Name:</strong> ${data.buyer_name}</p>
                <p><strong>Address:</strong> ${data.buyer_address}</p>
                <p><strong>Email:</strong> ${data.buyer_email}</p>
                <p><strong>Contact Info:</strong> ${data.contact_info}</p>
                <p><strong>Reserved Slots:</strong> ${data.reserved_slots ? data.reserved_slots.join(', ') : 'None'}</p>
                <p><strong>Reserved Slots:</strong> ${data.owned_slots ? data.owned_slots.join(', ') : 'None'}</p>
            `;
                    document.getElementById('client-info-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching slot info:', error);
                    alert(error.message || 'Failed to load client information. Please try again.');
                });
        }

        function closeModal() {
            document.getElementById('client-info-modal').classList.add('hidden');
        }
    </script>
<script>
    function openAddSlotsModal() {
        document.getElementById('addSlotsModal').classList.remove('hidden');
    }

    function closeAddSlotsModal() {
        document.getElementById('addSlotsModal').classList.add('hidden');
    }
</script>
    <script>
        function toggleCollapse(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>
</x-app-layout>

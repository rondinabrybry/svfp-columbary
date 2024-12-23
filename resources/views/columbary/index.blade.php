<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Columbary Slots') }}
            </h2>
            <form action="{{ route('columbary.loadIndex') }}" method="GET" class="mt-4">
                <button
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Load All Data
                </button>
            </form>
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
                                                            <span class="px-2 py-1 rounded-full text-white 
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
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            @if ($slot && $slot->status === 'Available')
                                                            
                                                                <form
                                                                    action="{{ route('columbary.reserve', $slot->id) }}"
                                                                    method="POST" class="flex items-center gap-2">
                                                                    @csrf
                                                                    <input type="text" name="buyer_name"
                                                                        placeholder="Buyer Name" required
                                                                        class="text-black border border-gray-300 px-2 py-1 rounded focus:outline-none focus:ring focus:border-blue-500">
                                                                    <input type="number" name="contact_info"
                                                                        placeholder="Contact Info" required
                                                                        class="text-black border border-gray-300 px-2 py-1 rounded focus:outline-none focus:ring focus:border-blue-500">
                                                                    <button type="submit"
                                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                                                        Reserve
                                                                    </button>
                                                                </form>
                                                            @elseif($slot && $slot->status === 'Reserved')
                                                            
                                                                <button onclick="openModal({{ $slot->id }})"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                                                    View
                                                                </button>

                                                                @if ($slot->payment)
                                                                    <form
                                                                        action="{{ route('columbary.paid', $slot->payment->id) }}"
                                                                        method="POST" class="inline-block">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                                                            onclick="return confirm('Mark this slot as paid?')">
                                                                            Mark as Paid
                                                                        </button>
                                                                    </form>
                                                                @endif
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
                <p><strong>Contact Info:</strong> ${data.contact_info}</p>
                <p><strong>Reserved Slots:</strong> ${data.reserved_slots ? data.reserved_slots.join(', ') : 'None'}</p>
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
        function toggleCollapse(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
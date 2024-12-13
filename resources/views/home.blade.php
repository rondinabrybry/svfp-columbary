<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Columbary Slots') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl text-black dark:text-white container mx-auto p-4">
        <div class="legends flex flex-row gap-2 mb-4">
            <p class="text-xs bg-white p-2 text-black font-bold rounded-lg">Available</p>
            <p class="text-xs bg-[#ef4444] p-2 text-white font-bold rounded-lg">Not Available</p>
            <p class="text-xs bg-[#3b82f6] p-2 text-white font-bold rounded-lg">Reserved</p>
            <p class="text-xs bg-[#facc15] p-2 text-black font-bold rounded-lg">Sold</p>
        </div>
        @foreach ($slots as $floor => $floorVaults)
    <div class="floor mb-8">
        <h2 class="text-2xl text-black dark:text-white font-semibold mb-4">Floor {{ $floor }}</h2>
        <div class="vaults flex flex-wrap gap-6">
            @foreach ($floorVaults as $vault => $vaultSlots)
                <div class="vault border rounded-lg p-4">
                    <h3 class="text-lg font-medium mb-2 text-center">Rack {{ $vault }}</h3>
                    
                    <style>
                        .slots {
                            display: flex;
                            flex-direction: row; /* Align columns horizontally */
                            gap: 1rem; /* Gap between columns */
                        }

                        .column {
                            display: flex;
                            flex-direction: column; /* Align slots vertically */
                            gap: 0.5rem; /* Gap between slots */
                        }

                        .slot {
                            display: flex; /* Flexbox for centering content */
                            align-items: center;
                            justify-content: center;
                            width: 3rem; /* Fixed width */
                            height: 3rem; /* Fixed height */
                            border: 1px solid #000; /* Example border for visibility */
                            text-align: center;
                            font-size: 0.875rem; /* Text size */
                            font-weight: bold;
                            cursor: pointer;
                        }

                        /* Dynamic styling based on status */
                        .active {
                            background-color: #4caf50;
                            color: white;
                        }

                        .inactive {
                            background-color: #f44336;
                            color: white;
                        }
                    </style>

                    <div class="slots">
                        @php
                            $columns = array_chunk($vaultSlots->toArray(), 6); // Divide slots into columns of 6
                        @endphp

                        @foreach ($columns as $column)
                            <div class="column">
                                @foreach ($column as $slot)
                                    <div class="slot {{ strtolower(str_replace(' ', '-', $slot['status'])) }}"
                                        data-slot-id="{{ $slot['id'] }}"
                                        data-slot-number="{{ $slot['slot_number'] }}">
                                        {{ $slot['slot_number'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

    </div>

    <!-- Modal (remains the same as in the previous version) -->
    <div id="reservationModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <div class="modal-header flex justify-between items-center border-b pb-3">
                <h5 id="reservationModalLabel" class="text-xl font-semibold">Reserve Slot</h5>
                <button id="closeModal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <div class="modal-body mt-4">
                <form id="reservationForm">
                    @csrf
                    <input type="hidden" name="slot_id" id="slotId">
                    <div class="mb-4">
                        <label for="buyerName" class="block text-sm font-medium mb-1">Your Name</label>
                        <input type="text" class="form-input w-full rounded border-gray-300" id="buyerName" name="buyer_name" required>
                    </div>
                    <div class="mb-4">
                        <label for="contactInfo" class="block text-sm font-medium mb-1">Contact Number</label>
                        <input type="text" class="form-input w-full rounded border-gray-300" id="contactInfo" name="contact_info" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Reserve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .slot.available {
            background-color: #ffffff;
            color: #000000;
        }
        .slot.reserved {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .slot.sold {
            background-color: #facc15;
        }
        .slot.not-available {
            background-color: #ef4444;
            color: #ffffff;
        }
        .slot:not(.available) {
            pointer-events: none;
            opacity: 0.5;
        }
        .vaults {
            display: flex;
            flex-wrap: wrap;
        }
        .vault {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slots = document.querySelectorAll('.slot.available');
            const modal = document.getElementById('reservationModal');
            const closeModalButton = document.getElementById('closeModal');
            const cancelModalButton = document.getElementById('cancelModal');
            const reservationForm = document.getElementById('reservationForm');
            const slotIdInput = document.getElementById('slotId');
            const buyerNameInput = document.getElementById('buyerName');
            const contactInfoInput = document.getElementById('contactInfo');

            // Open modal
            slots.forEach(slot => {
                slot.addEventListener('click', function () {
                    const slotId = this.getAttribute('data-slot-id');
                    const slotNumber = this.getAttribute('data-slot-number');
                    document.getElementById('reservationModalLabel').innerText = `Reserve Slot ${slotNumber}`;
                    slotIdInput.value = slotId;
                    buyerNameInput.value = '';
                    contactInfoInput.value = '';
                    modal.classList.remove('hidden');
                });
            });

            // Close modal
            [closeModalButton, cancelModalButton].forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });

            // Handle form submission
            reservationForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("{{ route('reserve.slot') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload to update the slots
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</x-app-layout>

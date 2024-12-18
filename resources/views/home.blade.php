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

        <style>
            .slots {
                display: flex;
                flex-direction: row;
                gap: 1rem;
            }

            .column {
                display: flex;
                flex-direction: column-reverse;
                gap: 0.5rem;
            }

            .vaults {
                overflow-x: auto;
            }

            .slot {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 3rem;
                height: 3rem;
                border: 1px solid #000;
                text-align: center;
                font-size: 0.875rem;
                font-weight: bold;
                cursor: pointer;
            }

            .vault.hidden {
                display: none;
            }

            /* Loading spinner styles */
            .loading-spinner {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner"></div>

        <div id="slotsContainer" style="display: none;">
            @foreach ($slots as $floor => $floorVaults)
                <div class="floor mb-8">
                    <h2 class="text-2xl text-black dark:text-white font-semibold mb-4">Floor {{ $floor }}</h2>
                    <div class="flex flex-row gap-6">
                        @for ($i = 1; $i <= count($floorVaults); $i++)
                            <p id="floor-{{ $floor }}-rack-{{ $i }}"
                                class="floor-count bg-white rounded-lg px-4 py-2 text-black cursor-pointer">
                                {{ $i }}
                            </p>
                        @endfor
                    </div>

                    <div class="vaults flex flex-wrap gap-6">
                        @foreach ($floorVaults as $vault => $vaultSlots)
                            <div class="vault hidden border rounded-lg p-4" id="f{{ $floor }}-r{{ $vault }}">
                                <h3 class="text-lg font-medium mb-2"> Rack {{ $vault }}</h3>
                                <div class="slots" id="slots-{{ $floor }}-r{{ $vault }}">
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

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hide the loading spinner and show the slots container once the page is loaded
            const loadingSpinner = document.getElementById('loadingSpinner');
            const slotsContainer = document.getElementById('slotsContainer');

            // Hide the loading spinner and show the slots container after 2 seconds (simulating loading)
            setTimeout(function () {
                loadingSpinner.style.display = 'none';
                slotsContainer.style.display = 'block';
            }, 1000); // Simulating a 1-second load delay. Adjust as needed.

            // You can use AJAX to dynamically load the slots data, or simply rely on the initial load.
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const rackCounts = document.querySelectorAll('.floor-count');

    rackCounts.forEach(function(count) {
        count.addEventListener('click', function() {
            const rackId = count.id.split('-');
            const floor = rackId[1];
            const rack = rackId[3];
            const slotContainer = document.getElementById('f' + floor + '-r' + rack);

            // Check if this slot is already open
            const isOpen = !slotContainer.classList.contains('hidden');

            // Close all open vaults first
            const openVaults = document.querySelectorAll('.vault:not(.hidden)');
            openVaults.forEach(function(vault) {
                vault.classList.add('hidden');
            });

            // If this slot was not already open, toggle it to open
            if (!isOpen) {
                slotContainer.classList.remove('hidden');
            }
        });
    });
});

    </script>

    <!-- Modal remains the same as previous version -->
    <div id="reservationModal"
        class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <div class="modal-header flex justify-between items-center border-b pb-3">
                <h5 id="reservationModalLabel" class="text-xl font-semibold">Reserve Slot</h5>
                <button type="button" id="closeModal"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
            <div class="modal-body mt-4">
                @php
                $manilaTime = \Carbon\Carbon::now('Asia/Manila')->format('M d, Y h:i A');
            @endphp
            
            
                <!-- Content will be injected dynamically here -->
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
            pointer-events: none;
            opacity: .75;
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
        document.addEventListener('DOMContentLoaded', function() {
            const availableSlots = document.querySelectorAll('.slot.available');
            const reservedSoldSlots = document.querySelectorAll('.slot.reserved, .slot.sold');
            const modal = document.getElementById('reservationModal');
            const closeModalButton = document.getElementById('closeModal');
            const cancelModalButton = document.getElementById('cancelModal');
            const modalBody = modal.querySelector('.modal-body');

            // Function to render the reservation form
            function renderReservationForm(slotId, slotNumber) {
                const formHtml = `
                <form id="reservationForm">
                    @csrf
                    <input type="hidden" name="slot_id" id="slotId" value="${slotId}">


                    <div class="mb-4">
                    <p>{{ $manilaTime }}</p>
                        <!-- Buyer Name Input -->
                        <label for="buyerName" class="block text-sm font-medium mb-1">Your Name</label>
                        <input type="text" class="form-input w-full rounded border-gray-300" id="buyerName" name="buyer_name" required>
                    </div>

                    <div class="mb-4">
                        <label for="contactInfo" class="block text-sm font-medium mb-1">Contact Number</label>
                        <input type="text" class="form-input w-full rounded border-gray-300" id="contactInfo"
                            name="contact_info" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Reserve</button>
                    </div>
                </form>
            `;
                modal.querySelector('#reservationModalLabel').innerText = `Reserve Slot ${slotNumber}`;
                modalBody.innerHTML = formHtml;

                // Re-attach form submission event listener
                const reservationForm = document.getElementById('reservationForm');
                reservationForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch("{{ route('reserve.slot') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            location.reload(); // Reload to update the slots
                        })
                        .catch(error => console.error('Error:', error));
                });
            }

            // Function to render the slot details
            function renderSlotDetails(slotId) {
                fetch(`/slot-details/${slotId}`)
                    .then(response => response.json())
                    .then(data => {
                        const detailsHtml = `
                        <div class="slot-details">
                            <p><strong>Floor:</strong> ${data.floor}</p>
                            <p><strong>Vault:</strong> ${data.vault}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                            <p><strong>Buyer Name:</strong> ${data.buyerName || 'N/A'}</p>
                            <p><strong>Contact Info:</strong> ${data.contactInfo || 'N/A'}</p>
                            <p><strong>Payment Status:</strong> ${data.paymentStatus || 'N/A'}</p>
                            <p><strong>Payment Date:</strong> ${data.paymentDate || 'N/A'}</p>
                        </div>
                    `;
                        modal.querySelector('#reservationModalLabel').innerText =
                            `Slot ${data.slotNumber} Details`;
                        modalBody.innerHTML = detailsHtml;

                        const closeBtnHtml = `
                        <button type="button" id="cancelModal"></button>
                    `;
                        modalBody.innerHTML += closeBtnHtml;

                        // Re-attach the close button event
                        document.getElementById('cancelModal').addEventListener('click', () => {
                            modal.classList.add('hidden');
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching slot details:', error);
                        alert('Unable to fetch slot details');
                    });
            }

            // Open modal for available slots (Reserve Slot)
            availableSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    const slotId = this.getAttribute('data-slot-id');
                    const slotNumber = this.getAttribute('data-slot-number');
                    renderReservationForm(slotId, slotNumber);
                    modal.classList.remove('hidden');
                });
            });

            // Open details modal for reserved or sold slots
            reservedSoldSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    const slotId = this.getAttribute('data-slot-id');
                    renderSlotDetails(slotId);
                    modal.classList.remove('hidden');
                });
            });

            // Close modal
            [closeModalButton, cancelModalButton].forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
        });
    </script>



</x-app-layout>

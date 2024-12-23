<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Columbary Dashboard') }}
        </h2>
    </x-slot>
    <style>
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div id="loadingSpinner" class="loading-spinner"></div>

    <div id="slotsContainer" style="display: none;">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-gray-800 text-black dark:text-white p-6 rounded-lg shadow-lg">
                    <div class="legends flex flex-row gap-2 mb-4">
                    <h3 class="text-lg font-semibold">Daily Sales</h3>
                    <p class="text-sm bg-[#facc15] p-1 px-4 text-white font-bold rounded-lg">Sold</p>
                    </div>
                    <p class="text-2xl font-bold">₱{{ number_format($dailySales, 2) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 text-black dark:text-white p-6 rounded-lg shadow-lg">
                    <div class="legends flex flex-row gap-2 mb-4">
                        <h3 class="text-lg font-semibold">Monthly Sales</h3>
                        <p class="text-sm bg-[#facc15] p-1 px-4 text-white font-bold rounded-lg">Sold</p>
                        </div>
                    <p class="text-2xl font-bold">₱{{ number_format($monthlySales, 2) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 text-black dark:text-white p-6 rounded-lg shadow-lg">
                    <div class="legends flex flex-row gap-2 mb-4">
                        <h3 class="text-lg font-semibold">All-time Sales</h3>
                        <p class="text-sm bg-[#facc15] p-1 px-4 text-white font-bold rounded-lg">Sold</p>
                        </div>
                    <p class="text-2xl font-bold">₱{{ number_format($allTimeSales, 2) }}</p>
                </div>

                
                    <div
                        class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Columbary Slots Overview</h3>
                        <div class="space-y-2">
                            <p>Total Slots: <span class="font-bold">{{ $totalSlots }}</span></p>
                            <p>Available Slots: <span class="font-bold text-green-600">{{ $availableSlots }}</span></p>
                            <p>Not Available Slots: <span class="font-bold text-red-600">{{ $notAvailableSlots }}</span>
                            </p>
                            <p>Reserved Slots: <span class="font-bold text-yellow-600">{{ $reservedSlots }}</span></p>
                            <p>Sold Slots: <span class="font-bold text-blue-600">{{ $soldSlots }}</span></p>
                        </div>
                    </div>

                    <div
                        class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Financial Summary</h3>
                        <div class="space-y-2">
                            <p>Total Payments: <span class="font-bold">{{ $paidPayments }}</span></p>
                            <p>Total Value of Sold Slots: <span
                                    class="font-bold text-green-600">₱{{ number_format($totalValueOfSoldSlots, 2) }}</span>
                            </p>
                        </div>
                        <br>
                        <h3 class="text-lg font-semibold">Reserved Value</h3>
                        <div class="space-y-2">
                            <p>Total Reserved: <span class="font-bold">{{ $reservedPayments }}</span></p>
                            <p>Total Value of Reserved Slots: <span
                                    class="font-bold text-green-600">₱{{ number_format($totalValueOfReservedSlots, 2) }}</span>
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Slots by Floor</h3>
                        <div class="space-y-2">
                            @foreach ($slotsByFloor as $floor)
                                <p class="text-sm">Floor {{ $floor->floor_number }}: (Total:
                                    {{ $floor->total_slots }}) <br>
                                <div class="text-sm">
                                    <span class="text-green-600">{{ $floor->available_slots }} Available</span> |
                                    <span class="text-red-600">{{ $floor->notAvailable_slots }} Not Available</span> |
                                    <span class="text-yellow-600">{{ $floor->reserved_slots }} Reserved</span> |
                                    <span class="text-blue-600">{{ $floor->sold_slots }} Sold</span>
                                </div>
                                </p>
                            @endforeach
                        </div>
                    </div>
                


            </div>


                <div
                    class="mt-6 text-black dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Vault Details</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2">Floor</th>
                                    <th class="text-left p-2">Vault</th>
                                    <th class="text-left p-2">Total Slots</th>
                                    <th class="text-left p-2">Available</th>
                                    <th class="text-left p-2">Not Available</th>
                                    <th class="text-left p-2">Reserved</th>
                                    <th class="text-left p-2">Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slotsByFloorAndVault as $vault)
                                    <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-2">{{ $vault->floor_number }}</td>
                                        <td class="p-2">{{ $vault->vault_number }}</td>
                                        <td class="p-2">{{ $vault->total_slots }}</td>
                                        <td class="p-2 text-green-600">{{ $vault->available_slots }}</td>
                                        <td class="p-2 text-red-600">{{ $vault->notAvailable_slots }}</td>
                                        <td class="p-2 text-yellow-600">{{ $vault->reserved_slots }}</td>
                                        <td class="p-2 text-blue-600">{{ $vault->sold_slots }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $slotsByFloorAndVault->links() }}
                        </div>
                    </div>
                </div>


                <div
                    class="mt-6 text-black dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Payments</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2">Slot Number</th>
                                    <th class="text-left p-2">Buyer Name</th>
                                    <th class="text-left p-2">Contact Info</th>
                                    <th class="text-left p-2">Price</th>
                                    <th class="text-left p-2">Payment Status</th>
                                    <th class="text-left p-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentPayments as $payment)
                                    <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-2">{{ $payment->columbarySlot->slot_number }}</td>
                                        <td class="p-2">{{ $payment->buyer_name }}</td>
                                        <td class="p-2">{{ $payment->contact_info }}</td>
                                        <td class="p-2">₱{{ number_format($payment->columbarySlot->price, 2) }}</td>
                                        <td class="p-2">
                                            <span
                                                class="{{ $payment->payment_status == 'Paid'
                                                    ? 'text-green-600'
                                                    : ($payment->payment_status == 'Reserved'
                                                        ? 'text-yellow-600'
                                                        : 'text-red-600') }}">
                                                {{ $payment->payment_status }}
                                            </span>
                                        </td>
                                        <td class="p-2">{{ $payment->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 text-black dark:text-white grid md:grid-cols-2 gap-6">
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Slot Status Distribution</h3>
                        <canvas id="slotStatusChart" class="w-full h-64"></canvas>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Payment Status Distribution</h3>
                        <canvas id="paymentStatusChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loadingSpinner").style.display = "none";
            document.getElementById("slotsContainer").style.display = "block";
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            var slotCtx = document.getElementById('slotStatusChart').getContext('2d');
            new Chart(slotCtx, {
                type: 'pie',
                data: {
                    labels: ['Available', 'Not Available', 'Reserved', 'Sold'],
                    datasets: [{
                        data: [{{ $availableSlots }}, {{ $notAvailableSlots }},
                            {{ $reservedSlots }}, {{ $soldSlots }}
                        ],
                        backgroundColor: ['#10B981', '#F23242', '#3B82F6', '#FFD700']
                    }]
                }
            });

            var paymentCtx = document.getElementById('paymentStatusChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($paymentStatusDistribution->toArray())) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($paymentStatusDistribution->toArray())) !!},
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                    }]
                }
            });
        });
    </script>
</x-app-layout>

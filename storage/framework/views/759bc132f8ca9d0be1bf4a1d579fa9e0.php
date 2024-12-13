<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Columbary Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Slots Overview Card -->
                <div class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Columbary Slots Overview</h3>
                    <div class="space-y-2">
                        <p>Total Slots: <span class="font-bold"><?php echo e($totalSlots); ?></span></p>
                        <p>Available Slots: <span class="font-bold text-green-600"><?php echo e($availableSlots); ?></span></p>
                        <p>Not Available Slots: <span class="font-bold text-red-600"><?php echo e($notAvailableSlots); ?></span></p>
                        <p>Reserved Slots: <span class="font-bold text-yellow-600"><?php echo e($reservedSlots); ?></span></p>
                        <p>Sold Slots: <span class="font-bold text-blue-600"><?php echo e($soldSlots); ?></span></p>
                    </div>
                </div>

                <!-- Financial Summary Card -->
                <div class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold">Financial Summary</h3>
                    <div class="space-y-2">
                        <p>Total Payments: <span class="font-bold"><?php echo e($paidPayments); ?></span></p>
                        <p>Total Value of Sold Slots: <span class="font-bold text-green-600">₱<?php echo e(number_format($totalValueOfSoldSlots, 2)); ?></span></p>
                    </div>
<br>
                    <h3 class="text-lg font-semibold">Reserved Value</h3>
                    <div class="space-y-2">
                        <p>Total Reserved: <span class="font-bold"><?php echo e($reservedPayments); ?></span></p>
                        <p>Total Value of Reserved Slots: <span class="font-bold text-green-600">₱<?php echo e(number_format($totalValueOfReservedSlots, 2)); ?></span></p>
                    </div>
                </div>

                <!-- Slots by Floor Card -->
                <div class="bg-white text-black dark:text-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Slots by Floor</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $slotsByFloor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="text-sm">Floor <?php echo e($floor->floor_number); ?>: (Total: <?php echo e($floor->total_slots); ?>) <br>
                                <div class="text-sm">
                                    <span class="text-green-600"><?php echo e($floor->available_slots); ?> Available</span> |
                                    <span class="text-red-600"><?php echo e($floor->notAvailable_slots); ?> Not Available</span> |
                                    <span class="text-yellow-600"><?php echo e($floor->reserved_slots); ?> Reserved</span> |
                                    <span class="text-blue-600"><?php echo e($floor->sold_slots); ?> Sold</span>
                                </div>
                            </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

<!-- Vault Details -->
<div class="mt-6 text-black dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                <?php $__currentLoopData = $slotsByFloorAndVault; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vault): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="p-2"><?php echo e($vault->floor_number); ?></td>
                        <td class="p-2"><?php echo e($vault->vault_number); ?></td>
                        <td class="p-2"><?php echo e($vault->total_slots); ?></td>
                        <td class="p-2 text-green-600"><?php echo e($vault->available_slots); ?></td>
                        <td class="p-2 text-red-600"><?php echo e($vault->notAvailable_slots); ?></td>
                        <td class="p-2 text-yellow-600"><?php echo e($vault->reserved_slots); ?></td>
                        <td class="p-2 text-blue-600"><?php echo e($vault->sold_slots); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4">
            <?php echo e($slotsByFloorAndVault->links()); ?>

        </div>
    </div>
</div>


            <!-- Recent Payments -->
            <div class="mt-6 text-black dark:text-white bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                            <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="p-2"><?php echo e($payment->columbarySlot->slot_number); ?></td>
                                    <td class="p-2"><?php echo e($payment->buyer_name); ?></td>
                                    <td class="p-2"><?php echo e($payment->contact_info); ?></td>
                                    <td class="p-2">₱<?php echo e(number_format($payment->columbarySlot->price, 2)); ?></td> 
                                    <td class="p-2">
                                        <span
                                            class="<?php echo e($payment->payment_status == 'Paid'
                                                ? 'text-green-600'
                                                : ($payment->payment_status == 'Reserved'
                                                    ? 'text-yellow-600'
                                                    : 'text-red-600')); ?>">
                                            <?php echo e($payment->payment_status); ?>

                                        </span>
                                    </td>
                                    <td class="p-2"><?php echo e($payment->created_at->format('M d, Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Slot Status and Payment Status Charts -->
            <div class="mt-6 text-black dark:text-white grid md:grid-cols-2 gap-6">
                <!-- Slot Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Slot Status Distribution</h3>
                    <canvas id="slotStatusChart" class="w-full h-64"></canvas>
                </div>

                <!-- Payment Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Status Distribution</h3>
                    <canvas id="paymentStatusChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Slot Status Chart
            var slotCtx = document.getElementById('slotStatusChart').getContext('2d');
            new Chart(slotCtx, {
                type: 'pie',
                data: {
                    labels: ['Available', 'Not Available', 'Reserved', 'Sold'],
                    datasets: [{
                        data: [<?php echo e($availableSlots); ?>, <?php echo e($notAvailableSlots); ?>,
                            <?php echo e($reservedSlots); ?>, <?php echo e($soldSlots); ?>

                        ],
                        backgroundColor: ['#10B981', '#F23242', '#3B82F6', '#FFD700']
                    }]
                }
            });

            // Payment Status Distribution Chart
            var paymentCtx = document.getElementById('paymentStatusChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_keys($paymentStatusDistribution->toArray())); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($paymentStatusDistribution->toArray())); ?>,
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                    }]
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/dashboard.blade.php ENDPATH**/ ?>
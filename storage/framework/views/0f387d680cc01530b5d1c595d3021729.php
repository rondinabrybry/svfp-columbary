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
            <?php echo e(__('Columbary Slots')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
<div class="py-12">
    <div class="max-w-7xl container mx-auto p-4">
        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Floors Section -->
        <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $floorSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-6">
            <!-- Collapsible Header -->
            <button 
                class="w-full flex justify-between items-center bg-blue-500 text-white px-4 py-3 rounded-lg shadow focus:outline-none"
                onclick="toggleCollapse('floor-<?php echo e($floor); ?>')">
                <span>Floor <?php echo e($floor); ?></span>
                <span>&#9660;</span>
            </button>

            <!-- Collapsible Content -->
            <div id="floor-<?php echo e($floor); ?>" class="hidden mt-2">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-center">Slot Number</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Buyer Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Payment Status</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $floorSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="text-white hover:bg-gray-800">
                                <td class="border border-gray-300 px-4 py-2 text-center"><?php echo e($slot->slot_number); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-white 
                                        <?php echo e($slot->status === 'Available' ? 'bg-green-500' : ($slot->status === 'Reserved' ? 'bg-yellow-500' : 'bg-red-500')); ?>">
                                        <?php echo e($slot->status); ?>

                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">₱<?php echo e(number_format($slot->price, 2)); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo e($slot->payment->buyer_name ?? 'N/A'); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <?php echo e($slot->payment->payment_status ?? 'N/A'); ?>

                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <?php if($slot->status === 'Available'): ?>
                                    <!-- Reservation Form -->
                                    <form action="<?php echo e(route('columbary.reserve', $slot->id)); ?>" method="POST" class="flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="text" name="buyer_name" placeholder="Buyer Name" required
                                            class="text-black border border-gray-300 px-2 py-1 rounded focus:outline-none focus:ring focus:border-blue-500">
                                        <input type="number" name="contact_info" placeholder="Contact Info" required
                                            class="text-black border border-gray-300 px-2 py-1 rounded focus:outline-none focus:ring focus:border-blue-500">
                                        <button type="submit"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                            Reserve
                                        </button>
                                    </form>
                                    <?php elseif($slot->status === 'Reserved'): ?>
                                    <!-- View Client Info Button -->
                                    <button onclick="openModal(<?php echo e($slot->id); ?>)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                        View
                                    </button>

                                    <form action="<?php echo e(route('columbary.paid', $slot->payment->id)); ?>" method="POST" class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                                onclick="return confirm('Mark this slot as paid?')">
                                            Mark as Paid
                                        </button>
                                    </form>
                                <?php endif; ?>
                                    
                                </td>
                                
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<!-- Modal -->
<div id="client-info-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden z-50 flex justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg w-3/4 md:w-1/2 p-6">
        <h2 class="text-xl font-bold mb-4">Client Information</h2>
        <div id="modal-content" class="space-y-4">
            <!-- Content will be dynamically loaded -->

        </div>

        <button onclick="closeModal()"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mt-4">
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\resources\views/columbary/index.blade.php ENDPATH**/ ?>
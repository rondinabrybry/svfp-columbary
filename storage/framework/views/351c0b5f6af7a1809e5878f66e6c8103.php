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
            <?php echo e(__('Columbary Slots Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <div class="py-12">
<div class="max-w-7xl container mx-auto p-4">
    <div class="flex justify-between items-center">
 
        
    </div>

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
    <div class="mb-4">
        <button onclick="openAddSlotsModal()" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Slots
        </button>
    </div>

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
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Slot Number</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                            <th class="border border-gray-300 px-4 py-2">Price</th>
                            <th class="border border-gray-300 px-4 py-2">Buyer Name</th>
                            <th class="border border-gray-300 px-4 py-2">Payment Status</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $floorSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($slot->status === 'Available' ? 'bg-green-50' : ($slot->status === 'Reserved' ? 'bg-yellow-50' : 'bg-red-50')); ?>">
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <?php echo e($slot->slot_number); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-white 
                                    <?php echo e($slot->status === 'Available' ? 'bg-green-500' : ($slot->status === 'Reserved' ? 'bg-yellow-500' : ($slot->status === 'Sold' ? 'bg-[#FFD700]' : 'bg-red-500'))); ?>">
                                    <?php echo e($slot->status); ?>

                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                ₱<?php echo e(number_format($slot->price, 2)); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php echo e($slot->payment->buyer_name ?? 'N/A'); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php echo e($slot->payment->payment_status ?? 'N/A'); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('columbary.edit', $slot->id)); ?>" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                        Edit
                                    </a>
                                

                                    <form action="<?php echo e(route('columbary.delete', $slot->id)); ?>" method="POST" class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                                onclick="return confirm('Are you sure you want to delete this slot?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
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
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">

        <!-- Existing slots display code remains the same -->

        <!-- Add Slots Modal -->
        <div id="addSlotsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Slots</h3>
                    
                    <form action="<?php echo e(route('columbary.create-slots')); ?>" method="POST" class="mt-4">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label for="floor_number" class="block text-sm font-bold text-gray-700 text-left">
                                Floor Number
                            </label>
                            <select name="floor_number" id="floor_number" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select Floor</option>
                                <?php for($i = 1; $i <= 4; $i++): ?>
                                    <option value="<?php echo e($i); ?>">Floor <?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="number_of_slots" class="block text-sm font-bold text-gray-700 text-left">
                                Number of Slots
                            </label>
                            <input type="number" name="number_of_slots" id="number_of_slots" 
                                   min="1" max="20" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-bold text-gray-700 text-left">
                                Price per Slot
                            </label>
                            <input type="number" name="price" id="price" step="0.01" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4 flex justify-between">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Slots
                            </button>
                            <button type="button" onclick="closeAddSlotsModal()"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\resources\views/columbary/list-slots.blade.php ENDPATH**/ ?>
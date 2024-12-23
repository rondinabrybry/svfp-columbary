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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Columbary Slots Management')); ?>

            </h2>
            <form action="<?php echo e(route('columbary.loadAll')); ?>" method="GET" class="mt-4">
                <button
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Load All Data
                </button>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl container mx-auto p-4">
            
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

            <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $vaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-6 text-black dark:text-white">
                
                    <button
                        class="w-full flex justify-between items-center bg-blue-500 text-white px-4 py-3 rounded-lg shadow focus:outline-none"
                        onclick="toggleCollapse('floor-<?php echo e($floor); ?>')">
                        <span>Floor <?php echo e($floor); ?></span>
                        <span>&#9660;</span>
                    </button>

                    <div id="floor-<?php echo e($floor); ?>" class="hidden mt-2"> 
                        <?php $__currentLoopData = $vaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vault => $vaultSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-4">
                                <button
                                    class="w-full flex justify-between items-center bg-green-500 text-white px-4 py-2 rounded-lg shadow focus:outline-none"
                                    onclick="toggleCollapse('floor-<?php echo e($floor); ?>-vault-<?php echo e($vault); ?>')">
                                    <span>Rack <?php echo e(chr(64 + $vault)); ?> </span>
                                    <span>&#9660;</span>
                                </button>

                                <div id="floor-<?php echo e($floor); ?>-vault-<?php echo e($vault); ?>" class="hidden mt-2">
                                    <div class="overflow-x-auto">
                                        <table class="table-auto w-full border-collapse border border-gray-300">
                                            <thead>
                                                <tr class="bg-gray-300 dark:bg-gray-700">
                                                    <th class="border border-gray-300 px-4 py-2 text-center">Slot Number</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Type</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Level</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Buyer Name</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Payment Status</th>
                                                    <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $vaultSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="text-black dark:text-white hover:bg-gray-400">
                                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                                            <?php echo e($slot->slot_number); ?>

                                                        </td>
                                                       <td class="border border-gray-300 px-4 py-2">
                                                            <?php echo e($slot->type); ?>

                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <?php echo e($slot->level_number); ?> 
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <span
                                                                class="px-2 py-1 rounded-full text-white 
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

                                                                <?php if($slot->status === 'Reserved'): ?>
                                                                <form action="<?php echo e(route('columbary.makeAvailable', $slot->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('PUT'); ?>
                                                                    <button
                                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                                                        type="submit">
                                                                        Remove Reserved
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            
                                                            <?php if(!in_array($slot->status, ['Not Available', 'Reserved']) && (($slot->payment && $slot->payment->payment_status !== 'Paid') || $slot->status !== 'Sold')): ?>
                                                            <form action="<?php echo e(route('columbary.markNotAvailable', $slot->id)); ?>" method="POST" class="inline-block">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PATCH'); ?>
                                                                <button type="submit"
                                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                                                    onclick="return confirm('Make this slot Not Available?')">
                                                                    Withhold
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        
                                                        
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div id="addSlotsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Slots</h3>
                <div class="mt-2">
                    <form id="addSlotsForm" method="POST" action="<?php echo e(route('columbary.create-slots')); ?>">
                        <?php echo csrf_field(); ?>
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/columbary/list-slots.blade.php ENDPATH**/ ?>
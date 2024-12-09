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
            <?php echo e(__('Edit Columbary Slot')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <div class="max-w-7xl container mx-auto p-6">

        <form action="<?php echo e(route('columbary.update', $slot->id)); ?>" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="slot_number">
                    Slot Number
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="slot_number" name="slot_number" type="text" value="<?php echo e($slot->slot_number); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="floor_number">
                    Floor Number
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="floor_number" name="floor_number" type="number" value="<?php echo e($slot->floor_number); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Price
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="price" name="price" type="number" step="0.01" value="<?php echo e($slot->price); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="status" name="status" required>
                    <option value="Available" <?php echo e($slot->status === 'Available' ? 'selected' : ''); ?>>Available</option>
                    <option value="Reserved" <?php echo e($slot->status === 'Reserved' ? 'selected' : ''); ?>>Reserved</option>
                    <option value="Sold" <?php echo e($slot->status === 'Sold' ? 'selected' : ''); ?>>Sold</option>
                    <option value="Not Available" <?php echo e($slot->status === 'Not Available' ? 'selected' : ''); ?>>Not
                        Available</option>
                </select>
            </div>

            <?php if($slot->payment): ?>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Payment Information</h3>
                    <p><strong>Buyer Name:</strong> <?php echo e($slot->payment->buyer_name); ?></p>
                    <p><strong>Contact Info:</strong> <?php echo e($slot->payment->contact_info); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo e($slot->payment->payment_status); ?></p>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Update Slot
                </button>
                <a href="<?php echo e(route('columbary.list')); ?>"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
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
<?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\resources\views/columbary/edit-slot.blade.php ENDPATH**/ ?>
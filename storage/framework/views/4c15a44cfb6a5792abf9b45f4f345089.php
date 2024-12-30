<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/settings.blade.php -->
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
            <?php echo e(__('Settings')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white bg-gray-800 rounded-lg p-4">
            <p class="mb-4">Select Any Levels from what Floor and Rack you want to disable in the pre-selling.</p>
            <form method="POST" action="<?php echo e(route('settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="flex space-x-4 mb-4">
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $racks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" class="collapsible bg-gray-700 text-white font-semibold py-2 px-4 rounded text-center">
                            Floor: <?php echo e($floor); ?>

                            <svg class="inline-block ml-2 w-4 h-4 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $racks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="content hidden bg-gray-600 p-4 rounded mb-4">
                        <div class="flex flex-row">
                            <?php $__currentLoopData = $racks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rack => $levels): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="ml-4 mb-2">
                                    <h4 class="font-semibold text-md"><?php echo e(chr(64 + $rack)); ?></h4>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="ml-8">
                                            <input type="checkbox" id="level_<?php echo e($floor); ?>_<?php echo e($rack); ?>_<?php echo e($level['level_number']); ?>" name="levels[]" value="<?php echo e($floor); ?>_<?php echo e($rack); ?>_<?php echo e($level['level_number']); ?>" <?php echo e($level['checked'] ? 'checked' : ''); ?>>
                                            <label for="level_<?php echo e($floor); ?>_<?php echo e($rack); ?>_<?php echo e($level['level_number']); ?>"><?php echo e($level['level_number']); ?></label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const collapsibles = document.querySelectorAll('.collapsible');
            const contents = document.querySelectorAll('.content');

            collapsibles.forEach((collapsible, index) => {
                collapsible.addEventListener('click', function () {
                    // Close all other contents
                    contents.forEach((content, contentIndex) => {
                        if (contentIndex !== index) {
                            content.classList.add('hidden');
                            collapsibles[contentIndex].querySelector('svg').classList.remove('rotate-180');
                        }
                    });

                    // Toggle the current content
                    contents[index].classList.toggle('hidden');
                    this.querySelector('svg').classList.toggle('rotate-180');
                });
            });
        });
    </script>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/settings.blade.php ENDPATH**/ ?>
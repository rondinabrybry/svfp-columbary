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

    <div class="max-w-7xl text-black dark:text-white container mx-auto p-4">
        <div class="legends flex flex-row gap-2 mb-4">
            <p class="text-xs bg-white p-2 text-black font-bold rounded-lg">Available</p>
            <p class="text-xs bg-[#ef4444] p-2 text-white font-bold rounded-lg">Not Available</p>
            <p class="text-xs bg-[#3b82f6] p-2 text-white font-bold rounded-lg">Reserved</p>
            <p class="text-xs bg-[#facc15] p-2 text-black font-bold rounded-lg">Sold</p>
        </div>
        <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $floorVaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="floor mb-8">
                <h2 class="text-2xl text-black dark:text-white font-semibold mb-4">Floor <?php echo e($floor); ?></h2>
                <div class="vaults flex flex-wrap gap-6">
                    <?php $__currentLoopData = $floorVaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vault => $vaultSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="vault border rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-2 text-center">Rack <?php echo e($vault); ?></h3>
                            <div class="slots grid grid-rows-6 gap-2">
                                <?php $__currentLoopData = $vaultSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div 
                                        class="slot w-12 h-12 flex items-center justify-center border text-sm font-bold cursor-pointer <?php echo e(strtolower(str_replace(' ', '-', $slot->status))); ?>"
                                        data-slot-id="<?php echo e($slot->id); ?>"
                                        data-slot-number="<?php echo e($slot->slot_number); ?>">
                                        <?php echo e($slot->slot_number); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php echo csrf_field(); ?>
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

                fetch("<?php echo e(route('reserve.slot')); ?>", {
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
<?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/home.blade.php ENDPATH**/ ?>
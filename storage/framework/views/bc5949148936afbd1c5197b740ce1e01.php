<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/home.blade.php -->
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
            <?php echo e(__('Columbary Slots 2')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl text-black dark:text-white container mx-auto p-4">
        <div class="legends flex flex-row gap-2 mb-6">
            <p class="text-xs bg-white p-2 text-black font-bold rounded-lg"><?php echo e(__('Available')); ?></p>
            <p class="text-xs bg-[#ef4444] p-2 text-white font-bold rounded-lg"><?php echo e(__('Not Available')); ?></p>
            <p class="text-xs bg-[#3b82f6] p-2 text-white font-bold rounded-lg"><?php echo e(__('Reserved')); ?></p>
            <p class="text-xs bg-[#facc15] p-2 text-black font-bold rounded-lg"><?php echo e(__('Sold')); ?></p>
        </div>

        <style>
            .slots {
                display: flex;
                flex-direction: column-reverse;
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
                width: 3.5rem;
                height: 3rem;
                border: 1px solid #000;
                text-align: center;
                font-size: 0.875rem;
                font-weight: bold;
                cursor: pointer;
            }

            .level {
                margin-bottom: 10px;
                display: flex;
            }

            .level-tag {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 3rem;
                height: 3rem;
                text-align: center;
                font-size: 0.7rem;
                font-weight: bolder;
                user-select: none;
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

            #reservationModal {
                color: black;
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

            .standard,
            .premium,
            .premium_plus {
                font-size: 0.7rem;
                padding: 0.25rem;
                border-radius: 0.25rem;
                color: #000000;
                font-weight: bold;
            }

            .vaults {
                display: flex;
                flex-wrap: wrap;
                position: relative;
            }

            .vault {
                display: flex;
                flex-direction: column;
            }

            .vault.active::before {
                content: '';
                position: absolute;
                top: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-width: 10px;
                border-style: solid;
                border-color: transparent transparent #ffffff transparent;
            }
        </style>

        <style>
            .floor {
                display: inline-block;
                margin-right: 20px;
                cursor: pointer;
                position: relative;
            }

            .floor.active::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-width: 10px;
                border-style: solid;
                border-color: transparent transparent #ffffff transparent;
            }

            .units {
                display: none;
            }

            .units.active {
                display: flex;
            }

            .floor-count {
                display: inline-block;
                margin-right: 10px;
            }

            .slot {
                border: 2px solid transparent;
                padding: 10px;
                margin: 5px;
                cursor: pointer;
                font-size: .800rem;
            }

            .slot.active {
                border-color: #000;
            }

            .vault.hidden {
                display: none;
            }

            .vault {
                display: block;
            }

            .vault.not-pop {
                display: none;
            }

            .floor-count.active {
                background-color: #4f46e5;
                /* Indigo color, you can change this */
                color: white;
            }
        </style>

        <div id="loadingSpinner" class="loading-spinner"></div>

        <div id="slotsContainer" style="display: none;">
            <div class="floors flex flex-row gap-6">
                <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $floorVaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="floor mb-8" data-floor="<?php echo e($floor); ?>">
                        <h2 class="text-2xl text-black dark:text-white font-semibold mb-4"><?php echo e(__('Floor')); ?>

                            <?php echo e($floor); ?></h2>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $floorVaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="units flex flex-row gap-6" id="units-<?php echo e($floor); ?>">
                    <?php for($i = 1; $i <= count($floorVaults); $i++): ?>
                        <?php if(isset($floorVaults[$i - 0]) && $floorVaults[$i - 0]->isNotEmpty()): ?>
                            <p id="floor-<?php echo e($floor); ?>-rack-<?php echo e($i); ?>"
                                class="floor-count bg-white rounded-lg px-4 py-2 text-black cursor-pointer">
                                <?php echo e(chr(64 + $floorVaults[$i - 0]->first()->unit_num_side)); ?>

                            </p>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="vaults flex flex-wrap gap-6">
                <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $floorVaults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $floorVaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vault => $vaultSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="vault border rounded-lg p-4 mt-4 not-pop"
                            id="f<?php echo e($floor); ?>-r<?php echo e($vault); ?>">
                            <h3 class="text-lg font-medium mb-4"> <?php echo e(__('Rack')); ?> <?php echo e(chr(64 + $vault)); ?> </h3>
                            <div class="slots" id="slots-<?php echo e($floor); ?>-r<?php echo e($vault); ?>">
                                <?php
                                    $levels = $vaultSlots->groupBy('level_number');
                                ?>
                                <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $levelSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="level">
                                        <p class="level-tag"><?php echo e(__('Level')); ?> <?php echo e($level); ?>:</p>
                                        <div class="level-slots flex gap-2">
                                            <?php $__currentLoopData = $levelSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="slot <?php echo e(strtolower(str_replace(' ', '-', $slot['status']))); ?>"
                                                    data-slot-id="<?php echo e($slot['id']); ?>"
                                                    data-slot-number="<?php echo e($slot['unit_id']); ?> <?php echo e($slot['type']); ?>">
                                                    <?php echo e($slot['unit_id']); ?>

                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const floorCounts = document.querySelectorAll('.floor-count');
                let activeElement = null;

                floorCounts.forEach(element => {
                    element.addEventListener('click', function() {
                        if (this === activeElement) {
                            // If clicking the same element, remove active class
                            this.classList.remove('active');
                            activeElement = null;
                        } else {
                            // Remove active class from previous element
                            if (activeElement) {
                                activeElement.classList.remove('active');
                            }
                            // Add active class to clicked element
                            this.classList.add('active');
                            activeElement = this;
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const vaults = document.querySelectorAll('.vault');
                vaults.forEach(vault => {
                    vault.classList.add('not-pop');
                });

                const floors = document.querySelectorAll('.floor');
                floors.forEach(floor => {
                    floor.addEventListener('click', function() {
                        const floorNumber = this.getAttribute('data-floor');
                        const units = document.querySelectorAll('.units');
                        const vaults = document.querySelectorAll('.vault');
                        units.forEach(unit => {
                            if (unit.id === `units-${floorNumber}`) {
                                unit.classList.toggle('active');
                                if (unit.classList.contains('active')) {
                                    this.classList.add('active');
                                } else {
                                    this.classList.remove('active');
                                }
                            } else {
                                unit.classList.remove('active');
                                document.querySelector(
                                        `.floor[data-floor="${unit.id.split('-')[1]}"]`)
                                    .classList.remove('active');
                            }
                        });
                        vaults.forEach(vault => {
                            vault.classList.add('hidden');
                            vault.classList.remove('not-pop');
                        });
                    });
                });

                const rackCounts = document.querySelectorAll('.floor-count');
                rackCounts.forEach(function(count) {
                    count.addEventListener('click', function() {
                        const rackId = count.id.split('-');
                        const floor = rackId[1];
                        const rack = rackId[3];
                        const slotContainer = document.getElementById('f' + floor + '-r' + rack);

                        const isOpen = !slotContainer.classList.contains('hidden');

                        const openVaults = document.querySelectorAll('.vault:not(.hidden)');
                        openVaults.forEach(function(vault) {
                            vault.classList.add('hidden');
                        });

                        if (!isOpen) {
                            slotContainer.classList.remove('hidden');
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("slotsContainer").style.display = "block";
            });
        </script>

        <div id="reservationModal"
            class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <div class="modal-header flex justify-between items-center border-b pb-3">
                    <h5 id="reservationModalLabel" class="text-xl font-semibold"><?php echo e(__('Reserve Unit')); ?></h5><br>
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"><?php echo e(__('Close')); ?></button>
                </div>
                <div class="modal-body mt-4">
                    <div id="manilaTime">
                        <!-- time -->
                    </div>
                </div>
                <script>
                    function updateManilaTime() {
                        const manilaTimeElement = document.getElementById('manilaTime');
                        const now = new Date();
                        const options = {
                            timeZone: 'Asia/Manila',
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: true
                        };
                        const manilaTime = now.toLocaleString('en-US', options);
                        manilaTimeElement.textContent = manilaTime;
                    }

                    setInterval(updateManilaTime, 1000);
                    updateManilaTime();
                </script>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const availableSlots = document.querySelectorAll('.slot.available');
                const reservedSoldSlots = document.querySelectorAll('.slot.reserved, .slot.sold');
                const modal = document.getElementById('reservationModal');
                const closeModalButton = document.getElementById('closeModal');
                const cancelModalButton = document.getElementById('cancelModal');
                const modalBody = modal.querySelector('.modal-body');

                function renderReservationForm(slotId, slotNumber) {
                    fetch(`/slot-details2/${slotId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.price) {
                                alert('Unable to fetch slot price');
                                return;
                            }

                            const formHtml = `
            <form id="reservationForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="slot_id" id="slotId" value="${slotId}">
                <input type="hidden" name="price" id="price">

                <div class="mb-4">
                    <p><strong>Price:</strong> ${data.price}</p>
                    <div id="manilaTime"></div>
                </div>
                <div class="mb-4">
                    <label for="buyerName" class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" class="form-input w-full rounded border-gray-300" id="buyerName" name="buyer_name" required>
                </div>

                <div class="mb-4">
                    <label for="buyerAddress" class="block text-sm font-medium mb-1">Address</label>
                    <input type="text" class="form-input w-full rounded border-gray-300" id="buyerAddress" name="buyer_address" required>
                </div>

                <div class="mb-4">
                    <label for="buyerEmail" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" class="form-input w-full rounded border-gray-300" id="buyerEmail" name="buyer_email" required>
                </div>

                <div class="mb-4">
                    <label for="contactInfo" class="block text-sm font-medium mb-1">Contact Number</label>
                    <input type="text" class="form-input w-full rounded border-gray-300" id="contactInfo"
                        name="contact_info" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Reservation Type</label>
                    <div>
                        <input type="checkbox" id="reserve" name="reservation_type" value="reserve">
                        <label for="reserve">Reserve</label>
                    </div>
                    <div>
                        <input type="checkbox" id="full" name="reservation_type" value="full">
                        <label for="full">Full</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" id="reserveButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Reserve</button>
                </div>
                <div id="loadingSpinner" class="loading-spinner" style="display: none;"></div>
            </form>
            `;

                            modal.querySelector('#reservationModalLabel').innerText = `Reserve Unit ${slotNumber}`;
                            modalBody.innerHTML = formHtml;

                            // Add the script after the form is rendered
                            const script = document.createElement('script');
                            script.innerHTML = `
                document.getElementById('reserve').addEventListener('change', function() {
                    if (this.checked) {
                        document.getElementById('full').checked = false;
                        document.getElementById('price').value = 10000;
                    }
                });

                document.getElementById('full').addEventListener('change', function() {
                    if (this.checked) {
                        document.getElementById('reserve').checked = false;
                        document.getElementById('price').value = ${data.price};
                    }
                });

                document.getElementById('reservationForm').addEventListener('submit', function(e) {
                    const reserveChecked = document.getElementById('reserve').checked;
                    const fullChecked = document.getElementById('full').checked;

                    if (!reserveChecked && !fullChecked) {
                        e.preventDefault();
                        alert('Please select at least one reservation type.');
                    }
                });
            `;
                            modalBody.appendChild(script);

                            const reservationForm = document.getElementById('reservationForm');
                            reservationForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                const formData = new FormData(this);

                                // Show loading spinner and disable button
                                const reserveButton = document.getElementById('reserveButton');
                                const loadingSpinner = document.getElementById('loadingSpinner');
                                reserveButton.disabled = true;
                                loadingSpinner.style.display = 'block';

                                fetch("<?php echo e(route('reserve.slot2')); ?>", {
                                        method: "POST",
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]')
                                                .getAttribute('content'),
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        alert(data.message);
                                        location.reload();
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('An error occurred while reserving the slot.');
                                    })
                                    .finally(() => {
                                        // Hide loading spinner and enable button
                                        reserveButton.disabled = false;
                                        loadingSpinner.style.display = 'none';
                                    });
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching slot details:', error);
                            alert('Unable to fetch slot details');
                        });
                }

                function renderSlotDetails(slotId) {
                    fetch(`/slot-details/${slotId}`)
                        .then(response => response.json())
                        .then(data => {
                            const getRackLetter = (vaultNumber) => String.fromCharCode(64 + vaultNumber);

                            const detailsHtml = `
            <div class="slot-details">
                <h1><strong>Price:</strong> ${data.price}</h1>
                <p><strong>Type:</strong> ${data.type}</p>
                <p><strong>Floor:</strong> ${data.floor}</p>
                <p><strong>Rack:</strong> ${getRackLetter(data.vault)}</p>
                <p><strong>Level:</strong> ${data.level}</p>
                <p><strong>Unit:</strong> ${data.unit}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Buyer Name:</strong> ${data.buyerName || 'N/A'}</p>
                <p><strong>Buyer Address:</strong> ${data.buyerAddress || 'N/A'}</p>
                <p><strong>Buyer Email:</strong> ${data.buyerEmail || 'N/A'}</p>
                <p><strong>Contact Info:</strong> ${data.contactInfo || 'N/A'}</p>
                <p><strong>Payment Status:</strong> ${data.paymentStatus || 'N/A'}</p>
                <p><strong>Payment Date:</strong> ${data.paymentDate || 'N/A'}</p>
            </div>
        `;
                            modal.querySelector('#reservationModalLabel').innerText =
                                `Unit ${data.unitId} Details`;
                            modalBody.innerHTML = detailsHtml;

                            const closeBtnHtml = `
            <button type="button" id="cancelModal"></button>
        `;
                            modalBody.innerHTML += closeBtnHtml;

                            document.getElementById('cancelModal').addEventListener('click', () => {
                                modal.classList.add('hidden');
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching slot details:', error);
                            alert('Unable to fetch slot details');
                        });
                }

                availableSlots.forEach(slot => {
                    slot.addEventListener('click', function() {
                        const slotId = this.getAttribute('data-slot-id');
                        const slotNumber = this.getAttribute('data-slot-number');
                        renderReservationForm(slotId, slotNumber);
                        modal.classList.remove('hidden');
                    });
                });

                reservedSoldSlots.forEach(slot => {
                    slot.addEventListener('click', function() {
                        const slotId = this.getAttribute('data-slot-id');
                        renderSlotDetails(slotId);
                        modal.classList.remove('hidden');
                    });
                });

                [closeModalButton, cancelModalButton].forEach(button => {
                    button.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });
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
<?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/home2.blade.php ENDPATH**/ ?>
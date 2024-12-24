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
            <?php echo e(__('Clients')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
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
        .bg-yellow-custom {
            background-color: rgb(161, 138, 5);
        }
        .bg-green-custom {
            background-color: green;
        }
        .superbold {
            font-weight: 1000;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .group {
        display: flex;
        line-height: 28px;
        align-items: center;
        position: relative;
        max-width: 400px;
        }

        .input {
        width: 100%;
        height: 40px;
        line-height: 28px;
        padding: 0 1rem;
        padding-left: 2.5rem;
        border: 2px solid transparent;
        border-radius: 8px;
        outline: none;
        background-color: #f3f3f4;
        color: #0d0c22;
        transition: 0.3s ease;
        }

        .input::placeholder {
        color: #9e9ea7;
        }

        .input:focus,
        input:hover {
        outline: none;
        border-color: rgba(0, 48, 73, 0.4);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgb(0 48 73 / 10%);
        }

        .icon {
        position: absolute;
        left: 1rem;
        fill: #9e9ea7;
        width: 1rem;
        height: 1rem;
        }

        .row-gap {
            row-gap: 30px;
        }
    </style>
    <div id="loadingSpinner" class="loading-spinner"></div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-black mb-4">
            <div class="group mb-4">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                    <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
                        ></path>
                    </g>
                </svg>
                <input id="searchInput" class="input" type="search" placeholder="Search Name" />
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white">
            <div class="bg-white dark:bg-gray-800 text-black dark:text-white p-6 rounded-lg shadow-lg">
                <div id="clientList">
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row-gap hover:bg-gray-700 px-4 py-2 rounded-lg flex flex-row gap-2">
                            <h3 class="text-lg font-semibold">
                                <a href="#" class="client-name" data-client="<?php echo e(json_encode($client)); ?>">
                                    -
                                    <?php echo e($client->buyer_name); ?>

                                </a>
                            </h3>
                            <p class="text-sm bg-yellow-custom p-1 px-4 text-white superbold rounded-lg"><?php echo e($client->paid_count); ?> Paid</p>
                            <p class="text-sm bg-green-custom p-1 px-4 text-white superbold rounded-lg"><?php echo e($client->reserved_count); ?> Reserved</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalClientName"></h2>
            <div id="modalReservedSlots">
                <h3>Reserved Slots</h3>
                <ul id="reservedSlotsList"></ul>
            </div>
            <div id="modalPaidSlots">
                <h3>Paid Slots</h3>
                <ul id="paidSlotsList"></ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loadingSpinner").style.display = "none";

            var modal = document.getElementById("clientModal");
            var span = document.getElementsByClassName("close")[0];

            document.querySelectorAll('.client-name').forEach(function(element) {
                element.addEventListener('click', function(event) {
                    event.preventDefault();
                    var client = JSON.parse(this.getAttribute('data-client'));
                    document.getElementById('modalClientName').innerText = client.buyer_name;

                    var reservedSlotsList = document.getElementById('reservedSlotsList');
                    reservedSlotsList.innerHTML = '';
                    client.reserved_slots.forEach(function(slot) {
                        var li = document.createElement('li');
                        li.innerText = slot.columbary_slot.unit_id + ' - ' + slot.columbary_slot.type;
                        reservedSlotsList.appendChild(li);
                    });

                    var paidSlotsList = document.getElementById('paidSlotsList');
                    paidSlotsList.innerHTML = '';
                    client.paid_slots.forEach(function(slot) {
                        var li = document.createElement('li');
                        li.innerText = slot.columbary_slot.unit_id + ' - ' + slot.columbary_slot.type;
                        paidSlotsList.appendChild(li);
                    });

                    modal.style.display = "block";
                });
            });

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var clientList = document.getElementById('clientList');
                var clients = clientList.getElementsByClassName('hover:bg-gray-700');

                Array.from(clients).forEach(function(client) {
                    var clientName = client.getElementsByClassName('client-name')[0].innerText.toLowerCase();
                    if (clientName.includes(filter)) {
                        client.style.display = '';
                    } else {
                        client.style.display = 'none';
                    }
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/clients.blade.php ENDPATH**/ ?>
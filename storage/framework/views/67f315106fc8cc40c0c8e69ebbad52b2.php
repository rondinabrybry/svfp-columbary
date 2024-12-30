<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/emails/forfeiture.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reservation Forfeited</title>
</head>
<body>
    <h1>Reservation Forfeited</h1>
    <p>Dear <?php echo e($buyer_name); ?>,</p>
    <p>We regret to inform you that your reservation for the following columbary slot has been forfeited due to non-payment within the 30-day period:</p>
    <ul>
        <li>Slot Number: <?php echo e($slot_number); ?></li>
        <li>Unit ID: <?php echo e($unit_id); ?></li>
        <li>Floor: <?php echo e($floor_number); ?></li>
        <li>Vault: <?php echo e($vault_number); ?></li>
        <li>Level: <?php echo e($level_number); ?></li>
        <li>Type: <?php echo e($type); ?></li>
        <li>Price: ₱<?php echo e(number_format($price, 2)); ?></li>
        <li>Reservation Price: ₱<?php echo e(number_format($unit_price, 2)); ?></li>
        <li>Purchase Date: <?php echo e($purchase_date); ?></li>
    </ul>
    <p>Please note that the reservation fee is not refundable.</p>
    <p>Thank you for your understanding.</p>
</body>
</html><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/emails/forfeiture.blade.php ENDPATH**/ ?>
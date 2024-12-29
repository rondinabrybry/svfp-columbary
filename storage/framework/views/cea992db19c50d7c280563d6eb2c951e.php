<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/emails/reminder.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Payment Reminder</title>
</head>
<body>
    <h1>Payment Reminder</h1>
    <p>Dear <?php echo e($buyer_name); ?>,</p>
    <p>This is a reminder that you have 10 days left to complete the payment for your reserved columbary slot. Here are the details of your reservation:</p>
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
    <p>Please complete the payment within the next 10 days to avoid forfeiture of your reservation.</p>
    <p>Thank you for choosing our services.</p>
</body>
</html><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/emails/reminder.blade.php ENDPATH**/ ?>
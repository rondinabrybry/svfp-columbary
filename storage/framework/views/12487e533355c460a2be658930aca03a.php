<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/emails/reservation.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reservation Confirmation</title>
</head>
<body>
    <h1>Reservation Confirmation</h1>
    <p>Dear <?php echo e($buyer_name); ?>,</p>
    <p>Thank you for reserving a columbary slot with us. Here are the details of your reservation:</p>
    <ul>
        <li>Unit ID: <?php echo e($unit_id); ?></li>
        <li>Type: <?php echo e($type); ?></li>
        <li>Unit Price: ₱<?php echo e(number_format($unit_price, 2)); ?></li>
        <li>Reservation Price: ₱<?php echo e(number_format($price, 2)); ?></li>
        <li>Reservation Date: <?php echo e($purchase_date); ?></li>
    </ul>
    <p>Please note that you have 30 days to complete the payment. If the payment is not received within 30 days, your reservation will be forfeited.</p>
    <p>Thank you for choosing our services.</p>
</body>
</html><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/emails/reservation.blade.php ENDPATH**/ ?>
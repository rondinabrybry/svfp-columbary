<!-- filepath: /c:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/emails/payment_complete.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Payment Complete - Ownership Confirmation</title>
</head>
<body>
    <h1>Congratulations, <?php echo e($buyer_name); ?>!</h1>
    <p>We are pleased to inform you that your payment for the columbary slot has been successfully completed. You are now the owner of the following unit:</p>
    <ul>
        <li>Unit ID: <?php echo e($unit_id); ?></li>
        <li>Type: <?php echo e($type); ?></li>
        <li>Price: ₱<?php echo e(number_format($price, 2)); ?></li>
        <li>Payment Date: <?php echo e($created_at); ?></li>
    </ul>
    <p>Thank you for your trust and confidence in our services. If you have any questions or need further assistance, please do not hesitate to contact us.</p>
    <p>Best regards,</p>
    <p>San Vicente Ferrer Liloan Service Cooperative (Savfelisco)</p>
</body>
</html><?php /**PATH C:\xampp\htdocs\LARAVEL\columbary\svfp\resources\views/emails/payment_complete.blade.php ENDPATH**/ ?>
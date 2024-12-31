<!-- filepath: /C:/xampp/htdocs/LARAVEL/columbary/svfp/resources/views/emails/forfeiture.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reservation Forfeited</title>
</head>
<body>
    <h1>Reservation Forfeited</h1>
    <p>Dear {{ $buyer_name }},</p>
    <p>We regret to inform you that your reservation for the following columbary slot has been forfeited due to non-payment within the 30-day period:</p>
    <ul>
        <li>Unit ID: {{ $unit_id }}</li>
        <li>Type: {{ $type }}</li>
        <li>Unit Price: ₱{{ number_format($unit_price, 2) }}</li>
        <li>Reservation Price: ₱{{ number_format($price, 2) }}</li>
        <li>Reservation Date: {{ $purchase_date }}</li>
    </ul>
    <p>Please note that the reservation fee is not refundable.</p>
    <p>Thank you for your understanding.</p>
</body>
</html>
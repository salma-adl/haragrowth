<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8E7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 30px;
            text-align: center;
        }

        .header {
            color: #1FBFAE;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .highlight {
            background-color: #FFF3CD;
            color: #000;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            display: inline-block;
            margin: 15px 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #FFC727;
            color: #fff;
            /* Diubah dari #000 ke #fff */
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">Halo, {{ $name }}</div>
        <p>Terima kasih telah melakukan pemesanan layanan di <strong>Hara Growth</strong>.</p>

        <p>Kode booking Anda:</p>
        <div class="highlight">{{ $booking_code }}</div>

        <p>Silakan klik tombol di bawah ini untuk melihat detail janji temu Anda.</p>

        <a href="{{ $url_book }}/{{ $booking_code }}" class="button">Lihat Janji Temu</a>

        <div class="footer">
            Salam hangat,<br>
            Tim Hara Growth
        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DLH Kabupaten Karanganyar Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #114232;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-body {
            padding: 20px;
            color: #333333;
        }

        .email-body h2 {
            color: #114232;
        }

        .email-footer {
            background-color: #114232;
            color: white;
            text-align: center;
            font-size: 12px;
            padding: 15px;
        }

        .info {
            background-color: #f0f0f0;
            border-left: 4px solid #114232;
            padding: 10px;
            margin: 15px 0;
        }

        .highlight {
            font-weight: bold;
            color: #114232;
        }

        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #114232;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Dinas Lingkungan Hidup<br>Kabupaten Karanganyar</h1>
        </div>

        <div class="email-body">
            <h2>Kode OTP Lupa Password</h2>
            <p>Halo,{{ $nama ?? 'Customer' }}</p>
            <p>Kami menerima permintaan untuk mengatur ulang sandi akun Anda.</p>
            <p>Gunakan kode OTP berikut untuk melanjutkan proses:</p>

            <div class="info">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p>Kode ini hanya berlaku selama <strong>5 menit</strong>. Demi keamanan, jangan bagikan kode ini ke siapa
                pun.</p>
            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        </div>

        <div class="email-footer">
            <p>Dinas Lingkungan Hidup Kabupaten Karanganyar</p>
            <p>Jl. Lawu No.204, Tegalasri, Bejen, Kec Karanganyar, Kab Karanganyar, Jawa Tengah 57716</p>
            <p>Telp: (0271) 495149 | Email: dlh@karanganyarkab.go.id</p>
            <p>&copy; 2025 ENTO ID</p>
        </div>
    </div>
</body>

</html>

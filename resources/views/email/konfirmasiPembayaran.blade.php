<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DLH Kabupaten Karanganyar Konfirmasi Pembayaran</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #014421;
            /* warna hijau gelap seperti di gambar footer */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .email-body {
            padding: 30px;
        }

        .email-body h2.success {
            color: #2e7d32;
        }

        .email-body h2.failed {
            color: #d32f2f;
        }

        .email-body .info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-left: 4px solid #014421;
            border-radius: 4px;
        }

        .email-body p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .email-footer {
            background-color: #014421;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>DLH Kabupaten Karanganyar</h1>
        </div>

        <div class="email-body">
            @if ($pembayaran->status_pembayaran === 'selesai')
                <h2 class="success">Pembayaran Anda Berhasil</h2>
                <p>Halo {{ $pembayaran->form_pengajuan->customer->nama ?? 'Customer' }},</p>
                <p>Terima kasih telah melakukan pembayaran. Berikut detail transaksi Anda:</p>
            @elseif($pembayaran->status_pembayaran === 'gagal')
                <h2 class="failed">Pembayaran Anda Gagal</h2>
                <p>Halo {{ $pembayaran->form_pengajuan->customer->nama ?? 'Customer' }},</p>
                <p>Maaf, pembayaran Anda gagal diproses. Berikut detail transaksi Anda:</p>
            @endif

            <div class="info">
                <p><strong>ID Order:</strong> {{ $pembayaran->id_order }}</p>
                <p><strong>Total Biaya:</strong> Rp {{ number_format($pembayaran->total_biaya, 0, ',', '.') }}</p>
                <p><strong>Status Pembayaran:</strong> {{ ucfirst($pembayaran->status_pembayaran) }}</p>
                <p><strong>Tanggal:</strong> {{ $pembayaran->updated_at->format('d M Y H:i') }}</p>
            </div>

            @if ($pembayaran->status_pembayaran === 'selesai')
                <p>Kami akan segera memproses pengajuan Anda.</p>
            @else
                <p>Silakan coba lagi atau hubungi kami jika masalah berlanjut.</p>
            @endif
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

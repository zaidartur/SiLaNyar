<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Uji Telah Tersedia</title>
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
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .email-header {
            background-color: #014421;
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

        .email-body p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-left: 4px solid #014421;
            border-radius: 4px;
        }

        .info p {
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            margin: 10px 5px 0 0;
            background-color: #2e7d32;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #25672a;
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
            <p>Halo <strong>{{ $hasil_uji->pengujian->form_pengajuan->customer->nama ?? 'Customer' }}</strong>,</p>
            <p>Hasil uji Anda telah selesai dan dapat diakses melalui tautan berikut. Silakan lihat rincian pengujian di bawah ini:</p>

            <div class="info">
                <p><strong>Nama Teknisi:</strong> {{ $hasil_uji->pengujian->pegawai->nama ?? '-' }}</p>
                <p><strong>Tanggal Pengujian:</strong> {{ \Carbon\Carbon::parse($hasil_uji->pengujian->tanggal_uji)->translatedFormat('d F Y') }}</p>
                <p><strong>Jam Mulai:</strong> {{ $hasil_uji->pengujian->jam_mulai }}</p>
                <p><strong>Jam Selesai:</strong> {{ $hasil_uji->pengujian->jam_selesai }}</p>
                <p><strong>Nama Customer:</strong> {{ $hasil_uji->pengujian->form_pengajuan->customer->nama ?? '-' }}</p>
                <p><strong>Kategori:</strong> {{ $hasil_uji->pengujian->form_pengajuan->kategori->nama ?? '-' }}</p>
                <p><strong>Jenis Cairan:</strong> {{ $hasil_uji->pengujian->form_pengajuan->jenis_cairan ?? '-' }}</p>
            </div>

            <p>
                <a href="{{ route('customer.hasil_uji.convert', $hasil_uji->id) }}" class="btn">Unduh Hasil Uji (PDF)</a>
                <a href="{{ route('customer.hasil_uji.detail', $hasil_uji->id) }}" class="btn" style="background-color: #00695c;">Kunjungi Detail Hasil Uji</a>
            </p>
        </div>

        <div class="email-footer">
            <p>Dinas Lingkungan Hidup Kabupaten Karanganyar</p>
            <p>Jl. Lawu No.204, Tegalasri, Bejen, Karanganyar, Jawa Tengah 57716</p>
            <p>Telp: (0271) 495149 | Email: dlh@karanganyarkab.go.id</p>
            <p>&copy; 2025 ENTO ID</p>
        </div>
    </div>
</body>
</html>

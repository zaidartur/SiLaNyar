<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hasil Uji #{{ $hasil_uji->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1, h2, h3 {
            color: #333;
        }
        
        h1 {
            font-size: 24px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        
        h2 {
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        h3 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        .info-box {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 10px;
        }
        
        .parameter-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .parameter-table th,
        .parameter-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .parameter-table th {
            background-color: #333;
            color: white;
        }
        
        .parameter-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .confidential {
            position: fixed;
            top: 40%;
            left: 20%;
            width: 100%;
            transform: rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body>
    @if ($is_customer)
        <div class="confidential">CONFIDENTIAL - CUSTOMER</div>
    @endif
    
    <div class="container">
        <h1>Hasil Uji #{{ $hasil_uji->id }}</h1>
        <p>Tanggal: {{ $tanggal }}</p>

        <!-- Informasi Umum -->
        <div class="info-box">
            <h2>Informasi Umum</h2>
            <div class="grid">
                <div>
                    <strong>Kode Hasil Uji:</strong> HU-{{ str_pad($hasil_uji->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div>
                    <strong>Kode Pengujian:</strong> {{ $hasil_uji->pengujian->kode_pengujian }}
                </div>
                <div>
                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $hasil_uji->status)) }}
                </div>
                <div>
                    <strong>Diupdate Oleh:</strong> {{ $hasil_uji->diupdate_oleh }}
                </div>
                <div>
                    <strong>Tanggal Dibuat:</strong> {{ date('d-m-Y H:i', strtotime($hasil_uji->created_at)) }}
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="info-box">
            <h2>Informasi Pengajuan</h2>
            <div class="grid">
                <div>
                    <strong>Kode Pengajuan:</strong> {{ $hasil_uji->pengujian->form_pengajuan->kode_pengajuan }}
                </div>
                <div>
                    <strong>Instansi:</strong> {{ $hasil_uji->pengujian->form_pengajuan->instansi->nama }}
                </div>
                <div>
                    <strong>Penanggung Jawab Instansi:</strong> {{ $hasil_uji->pengujian->form_pengajuan->instansi->user->nama }}
                </div>
                <div>
                    <strong>Kategori:</strong> {{ $hasil_uji->pengujian->form_pengajuan->kategori->nama }}
                </div>
                <div>
                    <strong>Teknisi:</strong> {{ $hasil_uji->pengujian->user->nama }}
                </div>
            </div>
        </div>

        <!-- Parameter Pengujian -->
        <div>
            <h2>Parameter Pengujian</h2>
            <table class="parameter-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Parameter</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                        <th>Baku Mutu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil_uji->pengujian->parameter_uji as $index => $param)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $param->nama_parameter ?? '-' }}</td>
                            <td>{{ $param->pivot->nilai ?? '-' }}</td>
                            <td>{{ $param->satuan ?? '-' }}</td>
                            <td>{{ $param->baku_mutu ?? '-' }}</td>
                            <td>{{ $param->pivot->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @if(count($hasil_uji->pengujian->parameter_uji) === 0)
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada parameter.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
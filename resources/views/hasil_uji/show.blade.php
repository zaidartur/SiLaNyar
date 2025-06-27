<!DOCTYPE html>
<html>

<head>
    
    <meta charset="UTF-8">
    <title>Laporan Hasil Uji Sementara</title>
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

        h1,
        h2,
        h3 {
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

        /* Styles for the header as per LHU */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            /* Added for visual separation like in LHU */
            padding-bottom: 10px;
        }

        .header img {
            max-width: 80px;
            /* Adjust as needed for logo */
            float: left;
            margin-right: 15px;
        }

        .header h3 {
            margin: 0;
            font-size: 18px;
            line-height: 1.2;
        }

        .header p {
            margin: 0;
            font-size: 12px;
        }

        /* LHU Specific Information Layout */
        .lhu-section {
            margin-bottom: 20px;
        }

        .lhu-section h2 {
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .lhu-section .item {
            margin-bottom: 5px;
            display: flex;
        }

        .lhu-section .item label {
            width: 150px;
            /* Adjust as needed for alignment */
            flex-shrink: 0;
        }

        .lhu-section .item span {
            flex-grow: 1;
        }

        .lhu-signature {
            margin-top: 50px;
            text-align: right;
            width: 300px;
            margin-left: auto;
        }

        .lhu-signature p {
            margin-bottom: 60px;
            /* Space for signature */
        }
    </style>
</head>

<body>
    @if ($is_customer)
        <div class="confidential">CONFIDENTIAL - CUSTOMER</div>
    @endif
    <div class="container">
        <div class="header">
            {{-- <img src="path/to/your/logo.png" alt="Logo Laboratorium"> --}}
            <h3>LABORATORIUM PENGUJI</h3>
            <h3>DINAS LINGKUNGAN HIDUP KABUPATEN KARANGANYAR</h3>
            <p>Jl KH Samanhudi No.5 Cangakan Karanganyar Tlp/Fax 0271-6491231</p>
            <p>Website :blh.karanganyarkab.go.id e-mail:blh@karanganyarkab.go.id</p>
            <br>
            <h3>LAPORAN HASIL UJI SEMENTARA No. Rek.: F/ LABLHKRA/ 7.8.3.1</h3>
        </div>

        <div class="lhu-section">
            <h2>A. DATA CONTOH UJI</h2>
            <div class="item">
                <label>1. Nomer Sampel</label><span>:
                    {{ $hasil_uji->pengujian->form_pengajuan->kode_pengajuan }}</span>
                </div>
            <div class="item">
                <label>2. Nama/Kode Sampel</label><span>:
                    {{ $hasil_uji->pengujian->form_pengajuan->kategori->nama }}/{{ $hasil_uji->pengujian->form_pengajuan->kategori->kode_kategori }}</span>
                </div>
            <div class="item">
                <label>3. Jenis Sampel</label><span>:
                    {{ $hasil_uji->pengujian->form_pengajuan->jenis_cairan->nama }}</span>
                </div>
            <div class="item">
                <label>4. Kemasan</label><span>: -</span>
                </div>
            
        </div>

        <div class="lhu-section">
            <h2>B. DATA ASAL</h2>
            <div class="item">
                <label>1. Pengirim sampel</label><span>:
                    {{ $hasil_uji->pengujian->form_pengajuan->jadwal->user->nama }}</span>
                </div>
            <div class="item">
                <label>2. Asal contoh uji</label><span>:
                    {{ $hasil_uji->pengujian->form_pengajuan->lokasi }}</span>
                </div>
            <div class="item">
                <label>3. Tanggal diterima</label><span>:
                    {{ date('d-m-Y', strtotime($hasil_uji->pengujian->form_pengajuan->jadwal->updated_at)) }}</span>
                </div>
            <div class="item">
                <label>4. Tanggal diuji</label><span>:
                    {{ date('d-m-Y', strtotime($hasil_uji->pengujian->tanggal_uji)) }}</span>
                </div>
            <div class="item">
                <label>5. Parameter</label><span>: -</span> {{-- This data point is represented in the table below, placeholder added as per LHU format --}}
                </div>
            <div class="item">
                <label>6. Baku mutu</label><span>: -</span> {{-- This data point is represented in the table below, placeholder added as per LHU format --}}
                </div>
            
        </div>

        <div>
            <h2>C. HASIL PENGUJIAN</h2>
            <table class="parameter-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Parameter</th>
                        <th>Satuan</th>
                        <th>Hasil Analisis</th>
                        <th>Baku Mutu Kadar Maksimum</th>
                        <th>Metode Uji</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach ($hasil_uji->pengujian->parameter_uji as $index => $param)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $param->nama_parameter ?? '-' }}</td>
                            <td>{{ $param->satuan ?? '-' }}</td>
                            <td>{{ $param->pivot->nilai ?? '-' }}</td>
                            <td>{{ $param->baku_mutu ?? '-' }}</td>
                            <td>{{ $param->pivot->keterangan ?? '-' }}</td>
                            {{-- Assuming 'keterangan' maps to 'Metode Uji' or is an additional field for it. If not, adjust accordingly --}}
                            </tr>
                    @endforeach
                    @if (count($hasil_uji->pengujian->parameter_uji) === 0)
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada parameter.
                            </td>
                            </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        <div class="lhu-signature">
            <p>Mengetahui</p>
            <p>Pengendali Teknik</p>
            <br><br><br>
            <p>(................................)</p>
            <p>Tanda tangan dan nama terang</p>
        </div>
        
    </div>
</body>

</html>
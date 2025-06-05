<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hasil Uji</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @if ($is_customer)
        <div
            style="
        position: fixed;
        top: 40%;
        left: 20%;
        width: 100%;
        transform: rotate(-45deg);
        font-size: 60px;
        color: rgba(0, 0, 0, 0.1);
        z-index: -1;
        pointer-events: none;
    ">
            CONFIDENTIAL - CUSTOMER
        </div>
    @endif
    <h1>Hasil Uji #{{ $hasil_uji->id }}</h1>
    <p>Tanggal: {{ $tanggal }}</p>

    <h2>Detail Pengujian</h2>
    <p>Kode Pengujian: {{ $hasil_uji->pengujian->kode_pengujian }}</p>

    <h3>Parameter</h3>
    <ul>
        @foreach ($hasil_uji->pengujian->parameter_uji as $param)
            <li>{{ $param->nama_parameter ?? '-' }}: {{ $param->pivot->nilai ?? '-' }} {{ $param->satuan ?? '' }}
                ({{ $param->pivot->keterangan ?? '-' }})</li>
        @endforeach
    </ul>
</body>

</html>

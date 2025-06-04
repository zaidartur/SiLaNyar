<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hasil Uji</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Hasil Uji #{{ $hasil_uji->id }}</h1>
    <p>Tanggal: {{ $tanggal }}</p>

    <h2>Detail Pengujian</h2>
    <p>Kode Pengujian: {{ $hasil_uji->pengujian->kode_pengujian }}</p>

    <h3>Parameter</h3>
    <ul>
        @foreach ($hasil_uji->pengujian->parameter_uji as $param)
            <li>{{ $param->nama_parameter ?? '-' }}: {{ $param->pivot->nilai ?? '-' }} {{ $param->satuan ?? '' }} ({{ $param->pivot->keterangan ?? '-' }})</li>
        @endforeach
    </ul>
</body>
</html>

@extends('test.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <form action="{{ url('kategori/store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama') }}" required>
            @error('nama')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="harga">Nama Kategori</label>
            <input type="number" id="harga" name="harga" class="form-control" value="{{ old('harga') }}" required>
            @error('harga')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ url('test/kategori') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection

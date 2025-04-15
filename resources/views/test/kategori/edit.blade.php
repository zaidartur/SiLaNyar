@extends('test.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <form action="{{ url('kategori/edit/'.$kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama', $kategori->nama) }}" required>
            @error('nama')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn">Update</button>
        <a href="{{ url('test/kategori') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection

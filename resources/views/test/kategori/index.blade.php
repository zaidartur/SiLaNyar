@extends('test.layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('test/kategori/create') }}" class="btn">Tambah Kategori</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <a href="{{ url('test/kategori/'.$item->id.'/edit') }}" class="btn btn-edit">Edit</a>
                    <form action="{{ url('kategori/'.$item->id) }}" method="POST" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center">Tidak ada data kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
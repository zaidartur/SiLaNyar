@extends('test.layouts.app')

@section('title', 'Daftar Jadwal')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url('test/jadwal/create') }}" class="btn">Tambah Jadwal</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Form Pengujian</th>
                <th>Waktu Pengambilan</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @php
                        $form = $form_pengajuan->firstWhere('id', $item->id_form_pengajuan);
                    @endphp
                    {{ $form ? $form->id : 'N/A' }}
                </td>
                <td>{{ $item->waktu_pengambilan }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>
                    <span style="padding: 3px 8px; border-radius: 3px; 
                          background-color: {{ $item->status == 'diproses' ? '#FFC107' : '#4CAF50' }}; 
                          color: white;">
                        {{ $item->status }}
                    </span>
                </td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    <a href="{{ url('test/jadwal/'.$item->id.'/edit') }}" class="btn btn-edit">Edit</a>
                    <form action="{{ url('jadwal/'.$item->id) }}" method="POST" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center">Tidak ada data jadwal</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
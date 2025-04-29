@extends('test.layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
    <form action="{{ url('jadwal/edit/'.$jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_form_pengajuan">Form Pengujian</label>
            <select id="id_form_pengajuan" name="id_form_pengajuan" class="form-control" required>
                <option value="">-- Pilih Form Pengujian --</option>
                @foreach($form_pengajuan as $form)
                <option value="{{ $form->id }}" {{ old('id_form_pengajuan', $jadwal->id_form_pengajuan) == $form->id ? 'selected' : '' }}>
                    {{ $form->id }} - {{ $form->created_at }}
                </option>
                @endforeach
            </select>
            @error('id_form_pengajuan')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="waktu_pengambilan">Waktu Pengambilan</label>
            <input type="datetime-local" id="waktu_pengambilan" name="waktu_pengambilan" class="form-control" 
                   value="{{ old('waktu_pengambilan', date('Y-m-d\TH:i', strtotime($jadwal->waktu_pengambilan))) }}" required>
            @error('waktu_pengambilan')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi', $jadwal->lokasi) }}" required>
            @error('lokasi')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="diproses" {{ old('status', $jadwal->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            @error('status')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" required>{{ old('keterangan', $jadwal->keterangan) }}</textarea>
            @error('keterangan')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn">Update</button>
        <a href="{{ url('test/jadwal') }}" style="margin-left: 10px;">Batal</a>
    </form>
@endsection
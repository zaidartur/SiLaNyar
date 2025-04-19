@extends('test.layouts.app')

@section('title', 'Test Dashboard')

@section('content')
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
            <h2>Kategori Management</h2>
            <p>Test CRUD operations for Kategori</p>
            <a href="{{ url('test/kategori') }}" class="btn">View Kategori</a>
        </div>
        
        <div style="flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
            <h2>Jadwal Management</h2>
            <p>Test CRUD operations for Jadwal</p>
            <a href="{{ url('test/jadwal') }}" class="btn">View Jadwal</a>
        </div>
    </div>
@endsection

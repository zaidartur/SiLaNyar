<!DOCTYPE html>
<html>
<head>
    <title>Test CRUD Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 8px 16px; margin-right: 5px; 
               text-decoration: none; background-color: #4CAF50; color: white; 
               border-radius: 4px; border: none; cursor: pointer; }
        .btn-edit { background-color: #2196F3; }
        .btn-delete { background-color: #f44336; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .error-text { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>@yield('title')</h1>
        
        @if(session('message'))
            <div class="alert success">{{ session('message') }}</div>
        @endif
        
        @yield('content')
        
        <div style="margin-top: 20px;">
            <a href="{{ url('/test') }}" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
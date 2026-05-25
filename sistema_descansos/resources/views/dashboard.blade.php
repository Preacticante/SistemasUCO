@extends('layouts.app')

@section('title', 'Panel Principal')

@push('styles')
    @include('dashboard.styles')
@endpush

@section('content')
    @include('dashboard.navbar')

    @php
        $path = public_path('img/logo_uco.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp

    <div style="max-width: 1200px; margin: 0 auto; padding: 20px 15px; position: relative;">
        
        <div style="position: absolute; left: 150px; top: 55px;">
            <img src="{{ $base64 }}" 
                 alt="UCO PREPA CONTEMPORÁNEA"
                 style="height: 150px; width: auto;">
        </div>

    <div class="container" style="margin-top: 40px;">
        <h1>Panel Principal</h1>

        @include('dashboard.stat-cards')
        @include('dashboard.alerts-table')
        @include('dashboard.employees-table')
    </div>
@endsection

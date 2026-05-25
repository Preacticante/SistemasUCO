@extends('layouts.app')

@section('title', 'Inicio')
@section('header', 'Dashboard General')

@push('styles')
    @include('dashboard.styles')
@endpush

@section('content')
    @php
        $path = public_path('img/logo_uco.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp

    <div class="container">
        <div style="margin-bottom: 30px;">
            <img src="{{ $base64 }}" 
                 alt="UCO PREPA CONTEMPORÁNEA"
                 style="height: 120px;"> 
        </div>

        @include('dashboard.stat-cards')
        @include('dashboard.alerts-table')
        @include('dashboard.employees-table')
    </div>
@endsection
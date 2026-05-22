@extends('screens.layout')

@section('title', 'Panel Principal')

@push('styles')
    @include('screens.dashboard.styles')
@endpush

@section('content')
    @include('screens.dashboard.navbar')

    <div style="padding-left: 40px; padding-top: 15px; position: absolute;">
        <img src="{{ asset('img/logo_uco2.png') }}" 
             alt="UCO PREPA CONTEMPORÁNEA"
             style="height: 200px;">
    </div>
    

    <div class="container">

        <h1>Panel Principal</h1>

        @include('screens.dashboard.stat-cards')
        @include('screens.dashboard.alerts-table')
        @include('screens.dashboard.employees-table')


        
        
    </div>
@endsection

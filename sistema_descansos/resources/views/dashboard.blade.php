@extends('layouts.app')

@section('title', 'Panel Principal')

@push('styles')
    @include('dashboard.styles')
@endpush

@section('content')
    @include('dashboard.navbar')

    <div class="container">
        <h1>Panel Principal</h1>

        @include('dashboard.stat-cards')
        @include('dashboard.alerts-table')
        @include('dashboard.employees-table')
    </div>
@endsection

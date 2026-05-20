@extends('screens.layout')

@section('title', 'Panel Principal')

@push('styles')
    @include('screens.dashboard.styles')
@endpush

@section('content')
    @include('screens.dashboard.navbar')

    <div class="container">
        <h1>Panel Principal</h1>

        @include('screens.dashboard.stat-cards')
        @include('screens.dashboard.alerts-table')
        @include('screens.dashboard.employees-table')
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Inicio')
@section('header', 'Dashboard General')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            font-family: 'Inter', sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
        }
        
        /* Tarjeta superior centrada */
        .dashboard-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 35px;
            text-align: center;
        }
        
        .dashboard-header h1 {
            margin: 0;
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .dashboard-header p {
            margin: 8px 0 0 0;
            color: #64748b;
            font-size: 1rem;
        }

        .dashboard-section { margin-top: 35px; }
    </style>
    @include('dashboard.styles')
@endpush

@section('content')
    <div class="dashboard-container">
        
        <div class="dashboard-header">
            <h1>Panel Principal</h1>
            <p>Resumen general del estado de vacaciones y personal activo.</p>
        </div>

        <div>
            @include('dashboard.stat-cards')
        </div>
        
        <div class="dashboard-section">
            @include('dashboard.alerts-table')
        </div>
        
        <div class="dashboard-section">
            @include('dashboard.employees-table')
        </div>

    </div>
@endsection
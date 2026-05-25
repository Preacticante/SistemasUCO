@extends('layouts.app')

@section('title', 'Configuración')
@section('header', 'Configuración del Sistema')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Ajustes Generales</h3>
            <p>Configuración del ciclo escolar y notificaciones.</p>
        </div>
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>Ley de Vacaciones</h3>
            <p>Aquí podrás modificar los días otorgados por años de antigüedad.</p>
        </div>
    </div>
@endsection
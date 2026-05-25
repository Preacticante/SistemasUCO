@extends('layouts.app')

@section('title', 'Mi Perfil')
@section('header', 'Perfil de Administrador')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 600px;">
        <h2>Información de la Cuenta</h2>
        <p><strong>Nombre:</strong> {{ session('nombre', 'Administrador') }}</p>
        <p><strong>Rol:</strong> Control de Recursos Humanos</p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <button style="background-color: #1e293b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
            Cambiar Contraseña
        </button>
    </div>
@endsection
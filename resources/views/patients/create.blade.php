<?php
use Illuminate\Support\Str;
?>
@extends('layouts.panel')

@section('content')

    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Generar Nuevo</h3>
                </div>
                <div class="col text-right">
                    <a href="{{ url('/pacientes') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-chevron-left"></i>
                        Volver</a>
                </div>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Por Favor!!! </strong>{{ $error }}
                    </div>
                @endforeach
            @endif

            <form action="{{ url('/pacientes') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo</label>
                    <input type="text" name="email" class="form-control" value={{ old('email') }}>
                </div>

                <div class="form-group">
                    <label for="identity_card">Cédula</label>
                    <input type="text" name="identity_card" class="form-control" value={{ old('identity_card') }}>
                </div>

                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" class="form-control" value={{ old('address') }}>
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value={{ old('phone') }}>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="text" name="password" class="form-control"
                        value="{{ old('password', Str::random(10)) }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Añadir</button>
            </form>
        </div>
    </div>
@endsection

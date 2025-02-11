@extends('layouts.panel')

@section('content')
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Cancelar Cita</h3>
                </div>
            </div>
            <div class="col text-right">
                <a href="{{ url('/miscitas') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-chevron-left"></i>
                    Volver</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('notification'))
                <div class="alert alert-success" role="alert">
                    {{ session('notification') }}
                </div>
            @endif
            @if ($role == 'paciente')
                <p>Estás a punto de cancelar tu cita reservada con el Médico <b>{{ $appointments->doctor->name }}</b>
                    (Especialidad
                    <b>{{ $appointments->specialty->name }}</b> ) para el día {{ $appointments->scheduled_date }}
                </p>
            @elseif($role == 'doctor')
                <p>Estás a punto de cancelar tu cita con el Paciente <b>{{ $appointments->patient->name }}</b> (Especialidad
                    <b>{{ $appointments->specialty->name }}</b> ) para el día {{ $appointments->scheduled_date }}
                    y la hora {{ $appointments->scheduled_time_12 }}
                </p>
            @elseif ($role == 'admin')
                <p>Estás a punto de cancelar la cita reservada por el Paciente <b>{{ $appointments->patient->name }}</b> con
                    el Médico <b>{{ $appointments->doctor->name }}</b> (Especialidad
                    <b>{{ $appointments->specialty->name }}</b> ) para el día {{ $appointments->scheduled_date }}
                </p>
            @endif

            <form action="{{ url('/miscitas/' . $appointments->id . '/cancel') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="justification">Especifique los Motivos de la Cancelación*</label>
                    <textarea name="justification" id="justification" cols="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Cancelar</button>
            </form>
        </div>
    </div>
@endsection

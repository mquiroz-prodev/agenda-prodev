@extends('layouts.panel')

@section('content')
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Detalles de la Cita</h3>
                    <p class="text-muted">Cita #{{ $appointments->id }}</p>
                </div>
                <div class="col text-right">
                    <a href="{{ url('/miscitas') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-chevron-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <i class="fas fa-calendar-alt"></i>
                    <strong>Fecha:</strong> {{ $appointments->scheduled_date }}
                </li>
                <li class="list-group-item">
                    <i class="fas fa-clock"></i>
                    <strong>Hora de Atención:</strong> {{ $appointments->scheduled_time_12 }}
                </li>
                @if ($role == 'paciente')
                    <li class="list-group-item">
                        <i class="fas fa-user-md"></i>
                        <strong>Doctor:</strong> {{ $appointments->doctor->name }}
                    </li>
                @elseif ($role == 'doctor')
                    <li class="list-group-item">
                        <i class="fas fa-user"></i>
                        <strong>Paciente:</strong> {{ $appointments->patient->name }}
                    </li>
                @elseif ($role == 'admin')
                    <li class="list-group-item">
                        <i class="fas fa-user-md"></i>
                        <strong>Doctor:</strong> {{ $appointments->doctor->name }}
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-user"></i>
                        <strong>Paciente:</strong> {{ $appointments->patient->name }}
                    </li>
                @endif

                <li class="list-group-item">
                    <i class="fas fa-stethoscope"></i>
                    <strong>Especialidad:</strong> {{ $appointments->specialty->name }}
                </li>
                <li class="list-group-item">
                    <i class="fas fa-notes-medical"></i>
                    <strong>Tipo de Consulta:</strong> {{ $appointments->type }}
                </li>
                <li class="list-group-item">
                    <i class="fas fa-info-circle"></i>
                    <strong>Estado:</strong>
                    @if ($appointments->status == 'Cancelada')
                        <span class="badge badge-danger">{{ $appointments->status }}</span>
                    @else
                        <span class="badge badge-success">{{ $appointments->status }}</span>
                    @endif
                </li>
                <li class="list-group-item">
                    <i class="fas fa-heartbeat"></i>
                    <strong>Síntomas:</strong> {{ $appointments->description }}
                </li>
            </ul>

            @if ($appointments->status == 'Cancelada')
            <div class="alert bg-light text-dark mt-4">
                <h3 class="text-primary"><i class="fas fa-exclamation-circle"></i> Detalles de la Cancelación</h3>
                @if ($appointments->cancellation)
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-calendar-times"></i>
                            <strong>Fecha de Cancelación:</strong> {{ $appointments->cancellation->created_at }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user"></i>
                            <strong>Nombre de quien Cancela:</strong> {{ $appointments->cancellation->cancelled_by->name }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-comment-alt"></i>
                            <strong>Justificación:</strong> {{ $appointments->cancellation->justification }}
                        </li>
                    </ul>
                @else
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-info-circle"></i> Esta cita fue cancelada antes de su
                            confirmación.</li>
                    </ul>
                @endif
            </div>
            @endif
        </div>
    </div>
@endsection

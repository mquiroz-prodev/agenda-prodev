<?php

namespace App\Interfaces;

use App\Models\Appointment;
use Illuminate\Http\Request;

interface AppointmentServiceInterface
{
    public function create(Appointment $appointment);
    public function getByPatient(int $patient_id);
    public function cancel(Appointment $appointment, int $cancelled_by, string $reason = '');
    public function confirm(Appointment $appointment);
    public function getAppointmentsByDoctorAndDate(string $date, int $doctorId): array;
}

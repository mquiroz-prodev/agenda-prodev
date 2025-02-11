<?php

namespace App\Services;

use App\Interfaces\AppointmentServiceInterface;
use App\Models\Appointment;
use App\Models\CancelledAppointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentService implements AppointmentServiceInterface
{
    public function create(Appointment $appointment)
    {
        $appointment = Appointment::create($appointment);

        return $appointment;
    }

    public function getByPatient($patient_id)
    {
        $appointments = Appointment::where('patient_id', $patient_id)->get();

        return $appointment;
    }

    public function cancel(Appointment $appointment, int $cancelled_by, string $reason = '')
    {
        
        $cancelledAppointment = new CancelledAppointment();
        $cancelledAppointment->appointment_id = $appointment->id;
        $cancelledAppointment->cancelled_by = $cancelled_by;
        $cancelledAppointment->reason = $reason;
        $cancelledAppointment->save();

        $appointment->update(['status' => 'Cancelada']);

        return $appointment;
    }

    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'Confirmada']);

        return $appointment;
    }

    public function getAppointmentsByDoctorAndDate(string $date, int $doctorId): array
    {
        $appointments = Appointment::where('scheduled_date', $date)
            ->where('doctor_id', $doctorId)
            ->get(['scheduled_time']);
        
        return $appointments;
    }
}
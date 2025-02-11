<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\AppointmentServiceInterface;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'scheduled_date' => 'required|date_format:Y-m-d',
                'scheduled_time' => 'required|date_format:H:i:s',
                'doctor_id' => 'required|exists:users,id',
                'patient_id' => 'required|exists:users,id',
                'specialty_id' => 'required|exists:specialties,id'
            ]);

            $appointmentService = new AppointmentService();
            $appointment = $appointmentService->create($validatedData);

            return response()->json(['success' => true, 'appointment' => $appointment]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al crear la cita'], 500);
        }
    }

    public function getByPatient(string $patient_id)
    {
        try {
            $appointmentService = new AppointmentService();
            $appointment = $appointmentService->getByPatient($patient_id);

            return response()->json(['success' => true, 'appointments' => $appointments]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener las citas del paciente'], 500);
        }
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        try {
            $appointmentService = new AppointmentService();
            $appointment = $appointmentService->cancel($appointment, $request);

            return response()->json(['success' => true, 'message' => 'Cita cancelada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al cancelar la cita'], 500);
        }
    }

    public function confirm(Appointment $appointment)
    {
        try {
            $appointmentService = new AppointmentService();
            $appointment = $appointmentService->confirm($appointment);

            return response()->json(['success' => true, 'message' => 'Cita confirmada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al confirmar la cita'], 500);
        }
    }

    public function getAppointmentsByDoctorAndDate(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'doctor_id' => 'required|integer',
                'date' => 'required|date_format:Y-m-d',
                // Agrega más reglas de validación según sea necesario
            ]);

            $appointmentService = new AppointmentService();
            $appointment = $appointmentService->getAppointmentsByDoctorAndDate($validatedData);

            return response()->json(['success' => true, 'appointments' => $appointments]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener las citas'], 500);
        }
    }

    /**
     * Reserva una cita para un paciente con un doctor en una hora específica.
     *
     * @param Request $request La solicitud HTTP que contiene los parámetros necesarios.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con el resultado de la operación.
     */
    public function reserveAppointment(Request $request)
    {
        try {
            // Validar parámetros
            $validatedData = $request->validate([
                'identity_card' => 'required|string',
                'doctor_name' => 'required|string',
                'start_time' => 'required|date_format:Y-m-d H:i:s',
            ]);

            // Verificar que el paciente exista y tenga el rol de paciente
            $patient = User::where('identity_card', $validatedData['identity_card'])
                ->where('role', 'paciente')->first();

            if (!$patient) {
                return response()->json(['success' => false, 'message' => 'Paciente no encontrado'], 404);
            }

            // Verificar que el doctor exista y tenga el rol de doctor
            $doctorName = $validatedData['doctor_name'];
            $doctor = User::where('name', 'like', "%{$doctorName}%")
                ->where('role', 'doctor')->first();

            if (!$doctor) {
                return response()->json(['success' => false, 'message' => 'Doctor no encontrado'], 404);
            }

            // Convertir start_time a objeto DateTime
            $startTime = new \DateTime($validatedData['start_time']);

            // Verificar que la hora esté disponible
            $existingAppointment = Appointment::where('doctor_id', $doctor->id)
                ->where('scheduled_date', $startTime->format('Y-m-d'))
                ->where('scheduled_time', $startTime->format('H:i:s'))
                ->first();

            if ($existingAppointment) {
                return response()->json(['success' => false, 'message' => 'La hora no está disponible'], 409);
            }

            // Crear la cita
            $appointmentData = [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'specialty_id' => 1,
                'scheduled_date' => $startTime->format('Y-m-d'),
                'scheduled_time' => $startTime->format('H:i:s'),
                'type' => 'presencial',
                'description' => 'Cita reservada por el paciente a través de la API',
            ];

            $appointment = Appointment::create($appointmentData);

            return response()->json(['success' => true, 'appointment' => $appointment]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al reservar la cita', 'trace' => $e->getMessage()], 500);
        }
    }
}

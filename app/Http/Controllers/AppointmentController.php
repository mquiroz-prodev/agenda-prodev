<?php

namespace App\Http\Controllers;

use App\Interfaces\HorarioServiceInterface;
use App\Models\Appointment;
use App\Models\CancelledAppointment;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{

    public function index()
    {
        $role = auth()->user()->role;

        // Definir variables con valores predeterminados
        $confirmedAppointments = [];
        $pendingAppointments = [];
        $oldAppointments = [];

        if ($role == 'admin') {
            // Administrador
            $confirmedAppointments = Appointment::where('status', 'Confirmada')->get();
            $pendingAppointments = Appointment::where('status', 'Reservada')->get();
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])->get();
        } elseif ($role == 'doctor') {
            // Medicos
            $confirmedAppointments = Appointment::where('status', 'Confirmada')
                ->where('doctor_id', auth()->id())
                ->get();
            $pendingAppointments = Appointment::where('status', 'Reservada')
                ->where('doctor_id', auth()->id())
                ->get();
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('doctor_id', auth()->id())
                ->get();
        } elseif ($role == 'paciente') {
            // Pacientes
            $confirmedAppointments = Appointment::where('status', 'Confirmada')
                ->where('patient_id', auth()->id())
                ->get();
            $pendingAppointments = Appointment::where('status', 'Reservada')
                ->where('patient_id', auth()->id())
                ->get();
            $oldAppointments = Appointment::whereIn('status', ['Atendida', 'Cancelada'])
                ->where('patient_id', auth()->id())
                ->get();
        }

        return view('appointments.index', compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments', 'role'));
    }

    public function create(HorarioServiceInterface $horarioServiceInterface)
    {
        $specialties = Specialty::all();

        $specialtyId = old('specialty_id');
        if ($specialtyId) {
            $specialty = Specialty::find($specialtyId);
            $doctors = $specialty->users;
        } else {
            $doctors = collect();
        }

        $date = old('scheduled_date');
        $doctorId = old('doctor_id');
        if ($date && $doctorId) {
            $intervals = $horarioServiceInterface->getAvailableIntervals($date, $doctorId);
        } else {
            $intervals = null;
        }

        return view('appointments.create', compact('specialties', 'doctors', 'intervals'));
    }

    public function store(Request $request, HorarioServiceInterface $horarioServiceInterface)
    {

        $rules = [
            'scheduled_time' => 'required',
            'type' => 'required',
            'description' => 'required',
            'doctor_id' => 'exists:users,id',
            'specialty_id' => 'exists:specialties,id'
        ];

        $messages = [
            'scheduled_time.required' => 'Debe Seleccionar una Hora Valida para su Cita.',
            'type.required' => 'Debe Seleccionar el Tipo de Consulta.',
            'description.required' => 'Debe poner sus sintomas.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request, $horarioServiceInterface) {

            $date = $request->input('scheduled_date');
            $doctorId = $request->input('doctor_id');
            $scheduled_time = $request->input('scheduled_time');

            if ($date && $doctorId && $scheduled_time) {
                $start = new Carbon($scheduled_time);
            } else {
                return;
            }

            if (!$horarioServiceInterface->isAvailableInterval($date, $doctorId, $start)) {
                $validator->errors()->add(
                    'available_time',
                    'La hora seleccionada ya se encuentra reservada por otro paciente.'
                );
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'scheduled_time',
            'scheduled_date',
            'type',
            'description',
            'doctor_id',
            'specialty_id'
        ]);

        $data['patient_id'] = auth()->id();

        $carbonTime = Carbon::createFromFormat('g:i A', $data['scheduled_time']);
        $data['scheduled_time'] = $carbonTime->format('H:i:s');

        Appointment::create($data);

        $notification = 'La Cita se ha Realizado Exitosamente.';

        return redirect('/miscitas')->with(compact('notification'));
    }

    public function cancel(Appointment $appointments, Request $request)
    {
        if ($request->has('justification')) {
            $cancellation = new CancelledAppointment();
            $cancellation->justification = $request->input('justification');
            $cancellation->cancelled_by_id = auth()->id();
            $appointments->cancellation()->save($cancellation);
        }

        $appointments->status = 'Cancelada';
        $appointments->save();

        $notification = 'La Cita se ha Cancelado Exitosamente.';

        return redirect('/miscitas')->with(compact('notification'));
    }

    public function formCancel(Appointment $appointments)
    {
        if ($appointments->status == 'Confirmada') {
            $role = auth()->user()->role;
            return view('appointments.cancel', compact('appointments', 'role'));
        }
        return redirect('/miscitas');
    }

    public function confirm(Appointment $appointments)
    {
        $appointments->status = 'Confirmada';
        $appointments->save();

        $notification = 'La Cita se ha Confirmado Exitosamente.';

        return redirect('/miscitas')->with(compact('notification'));
    }

    public function show(Appointment $appointments)
    {
        $role = auth()->user()->role;
        return view('appointments.show', compact('appointments', 'role'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\HorarioServiceInterface;
use App\Models\Horarios;
use App\Models\User;
use App\Services\HorarioService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function hours(Request $request, HorarioServiceInterface $horarioServiceInterface)
    {
        $rules = [
            'date' => 'required|date_format:"Y-m-d"',
            'doctor_id' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);

        $date = $request->input('date');
        $doctorId = $request->input('doctor_id');

        return $horarioServiceInterface->getAvailableIntervals($date, $doctorId);
    }

    public function getAvailableHorarios(Request $request)
    {
        $rules = [
            'doctor_id' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);

        $doctorId = $request->input('doctor_id');

        return $this->getHorarios($doctorId);
    }

    public function getAvailableHorariosByName(Request $request)
    {
        $rules = [
            'doctor_name' => 'required|string'
        ];

        $this->validate($request, $rules);

        $doctorName = $request->input('doctor_name');
        $doctor = User::where('name', 'like', "%{$doctorName}%")->where('role', 'doctor')->first();

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor no encontrado'], 404);
        }

        return $this->getHorarios($doctor->id);
    }

    private function getHorarios($doctorId)
    {
        $horarioService = new HorarioService();
        $date = Carbon::now();
        $horarios = [];

        // Intentar obtener horarios para la fecha actual y los próximos 3 días
        for ($i = 0; $i < 4; $i++) {
            $horarios = $horarioService->getAvailableIntervals($date->format('Y-m-d'), $doctorId);
            if (!empty($horarios)) {
                break;
            }
            $date->addDay();
        }

        // Responder con JSON
        return response()->json([
            'success' => true,
            'horarios' => $horarios,
            'date' => $date->format('Y-m-d')
        ]);
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\HorarioController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $token = $user->createToken('Access Token')->accessToken;

    return response()->json(['token' => $token]);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/horario/horas', [HorarioController::class, 'hours']);
    Route::get('/horarios', [HorarioController::class, 'getAvailableHorarios']);
    Route::get('/especialidades', [SpecialtyController::class, 'all']);
    Route::get('/especialidades/{specialty}/medicos', [SpecialtyController::class, 'doctors']);

    // Rutas para AppointmentController
    Route::post('/appointments', [AppointmentController::class, 'create']);
    Route::get('/appointments/patient/{patient_id}', [AppointmentController::class, 'getByPatient']);
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm']);
    Route::get('/appointments/doctor', [AppointmentController::class, 'getAppointmentsByDoctorAndDate']);
    Route::get('/especialidades/{specialty}/doctores-cercanos', [SpecialtyController::class, 'get_doctor_nearby']);
    Route::get('/horarios/doctor/nombre', [HorarioController::class, 'getAvailableHorariosByName']);

    Route::post('/appointments/reserve', [AppointmentController::class, 'reserveAppointment']);

});


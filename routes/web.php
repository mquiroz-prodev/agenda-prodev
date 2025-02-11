<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/especialidades', [App\Http\Controllers\admin\SpecialtyController::class, 'index']);

    Route::get('/especialidades/create', [App\Http\Controllers\admin\SpecialtyController::class, 'create']);
    Route::get('/especialidades/{specialty}/edit', [App\Http\Controllers\admin\SpecialtyController::class, 'edit']);
    Route::post('/especialidades', [App\Http\Controllers\admin\SpecialtyController::class, 'sendData']);

    Route::put('/especialidades/{specialty}', [App\Http\Controllers\admin\SpecialtyController::class, 'update']);
    Route::delete('/especialidades/{specialty}', [App\Http\Controllers\admin\SpecialtyController::class, 'destroy']);

    //Ruta de Medicos
    Route::resource('medicos','App\Http\Controllers\admin\DoctorController');

    //Ruta de Pacientes
    Route::resource('pacientes','App\Http\Controllers\admin\PatientController');

    //Ruta de Reportes
    Route::get('/reportes/citas/line', [App\Http\Controllers\admin\ChartController::class, 'appointments']);
    Route::get('/reportes/doctors/column', [App\Http\Controllers\admin\ChartController::class, 'doctors']);
    Route::get('/reportes/doctors/column/data', [App\Http\Controllers\admin\ChartController::class, 'doctorsJason']);
});


//Rutas de Medicos
Route::middleware(['auth', 'doctor'])->group(function ()
{
    Route::get('/horario', [App\Http\Controllers\doctor\HorarioController::class, 'edit']);
    Route::post('/horario', [App\Http\Controllers\doctor\HorarioController::class, 'store']);
});

//Rutas de Pacientes
Route::middleware('auth')->group(function()
{
    Route::get('/reservarcitas/create', [App\Http\Controllers\AppointmentController::class, 'create']);
    Route::post('/reservarcitas', [App\Http\Controllers\AppointmentController::class, 'store']);
    Route::get('/miscitas', [App\Http\Controllers\AppointmentController::class, 'index']);
    Route::get('/miscitas/{appointments}', [App\Http\Controllers\AppointmentController::class, 'show']);
    Route::post('/miscitas/{appointments}/cancel', [App\Http\Controllers\AppointmentController::class, 'cancel']);
    Route::post('/miscitas/{appointments}/confirm', [App\Http\Controllers\AppointmentController::class, 'confirm']);

    Route::get('/miscitas/{appointments}/cancel', [App\Http\Controllers\AppointmentController::class, 'formCancel']);
    //Json
    //Route::get('/especialidades/{specialty}/medicos', [App\Http\Controllers\Api\SpecialtyController::class, 'doctors']);
    //Route::get('/horario/horas', [App\Http\Controllers\Api\HorarioController::class, 'hours']);
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





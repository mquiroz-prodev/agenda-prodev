<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function doctors(Specialty $specialty)
    {
        return $specialty->users()->get(
            [
                'users.id',
                'users.name'
            ]
        );
    }

    public function all()
    {
        return Specialty::all([
            'id',
            'name',
            'description'
        ]);
    }

    // Obtiene los doctores cercanos
    /**
     * Obtiene doctores cercanos a una ubicación específica y filtrados por especialidad.
     *
     * @param float $latitude Latitud de la ubicación del usuario.
     * @param float $longitude Longitud de la ubicación del usuario.
     * @param string $specialty Nombre de la especialidad buscada.
     * @param int $maxDistance Distancia máxima en kilómetros para considerar un profesional "cercano".
     * @return array Listado de profesionales cercanos con sus datos y establecimientos asociados.
     */
    public function get_doctor_nearby(Request $request, Specialty $specialty)
    {
        // Validar parámetros
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'maxDistance' => 'sometimes|numeric|min:1'
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];
        $maxDistance = $validated['maxDistance'] ?? 10;

        $doctors = User::with([
            'specialties' => function ($query) use ($specialty) {
                $query->where('specialties.id', $specialty->id);
            },
            'institutions'
        ])->where('role', 'doctor')->get();
        
        $result = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'nombre' => $doctor->name,
                'email' => $doctor->email,
                'telefono' => $doctor->phone,
                'profesion' => $doctor->specialties->map(function ($specialty) {
                    return $specialty->name;
                })->toArray(),
                'establecimientos' => $doctor->institutions->map(function ($establishment) {
                    return [
                        'id' => $establishment->id,
                        'nombre' => $establishment->name,
                        'direccion' => $establishment->address,
                        'latitud' => $establishment->latitude,
                        'longitud' => $establishment->longitude,
                    ];
                })->toArray(),
            ];
        });

        return response()->json($result);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radio de la tierra en kilómetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lat2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}

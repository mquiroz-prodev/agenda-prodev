<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution;

class DoctorInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todas las instituciones
        $institutions = Institution::all();

        // Obtener todos los usuarios con el rol de doctor
        $doctors = User::where('role', 'doctor')->get();

        // Asignar una institución aleatoria a cada doctor
        foreach ($doctors as $doctor) {
            $doctor->institutions()->attach(
                $institutions->random()->id
            );
        }
    }
}

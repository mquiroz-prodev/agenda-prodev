<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutions = [
            [
                'name' => 'Hospital Las Higueras',
                'address' => 'Av. Colón 999, Talcahuano',
                'latitude' => -36.724,
                'longitude' => -73.116,
                'phone' => '412123456',
                'active' => true,
            ],
            [
                'name' => 'Hospital Regional de Concepción',
                'address' => 'Calle San Martín 123, Concepción',
                'latitude' => -36.826,
                'longitude' => -73.049,
                'phone' => '412654321',
                'active' => true,
            ],
            [
                'name' => 'Clínica San Pedro',
                'address' => 'Av. Michimalonco 456, San Pedro',
                'latitude' => -36.841,
                'longitude' => -73.103,
                'phone' => '412987654',
                'active' => true,
            ],
            [
                'name' => 'Centro Médico Chiguayante',
                'address' => 'Calle O\'Higgins 789, Chiguayante',
                'latitude' => -36.925,
                'longitude' => -73.028,
                'phone' => '412345678',
                'active' => true,
            ],
        ];

        foreach ($institutions as $institution) {
            Institution::create($institution);
        }
    }
}

<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Mario Quiroz',
            'email' => 'mquiroz@prodev.cl',
            'email_verified_at' => now(),
            'password' => bcrypt('mquiroz@prodev.cl'), // password
            'identity_card' =>'15517748-9',
            'address' =>'Jaime Repullo 3445, Casa 13, Talcahuano',
            'phone' => '+505 57196244',
            'role' =>'admin',
        ]);

        User::create([
            'name' => 'Teresa Ulloa',
            'email' => 'teresa.ulloa@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('teresa.ulloa@gmail.com'), // password
            'identity_card' =>'161-210501-1000F',
            'address' =>'del panteoncito el carmen 3c al sur y c3 al oeste',
            'phone' => '+505 57196245',
            'role' =>'doctor',
        ]);

        User::create([
            'name' => 'Pedro Perez',
            'email' => 'pedro.perez@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('pedro.perez@gmail.com'), // password
            'identity_card' =>'12345678-9',
            'address' =>'del panteoncito el carmen 3c al sur y c3 al oeste',
            'phone' => '+505 57196246',
            'role' =>'paciente',
        ]);

        User::factory()
        ->count(10)
        ->state(['role' => 'paciente'])
        ->create();
    }
}

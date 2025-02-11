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
            'name' => 'Steven Ulloa Gutierrez',
            'email' => 'ulloadeifheltsteven@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('ulloadeifheltsteven@gmail.com'), // password
            'identity_card' =>'161-210501-1000K',
            'address' =>'del panteoncito el carmen 3c al sur y c3 al oeste',
            'phone' => '+505 57196244',
            'role' =>'admin',
        ]);

        User::create([
            'name' => 'Deifhelt Ulloa',
            'email' => 'deifhelt.ulloa@tecnacional.edu.ni',
            'email_verified_at' => now(),
            'password' => bcrypt('deifhelt.ulloa@tecnacional.edu.ni'), // password
            'identity_card' =>'161-210501-1000F',
            'address' =>'del panteoncito el carmen 3c al sur y c3 al oeste',
            'phone' => '+505 57196245',
            'role' =>'doctor',
        ]);

        User::create([
            'name' => 'Eduardo Gamez',
            'email' => 'eduardo.gamez@tecnacional.edu.ni',
            'email_verified_at' => now(),
            'password' => bcrypt('eduardo.gamez@tecnacional.edu.ni'), // password
            'identity_card' =>'161-210501-1000V',
            'address' =>'del panteoncito el carmen 3c al sur y c3 al oeste',
            'phone' => '+505 57196246',
            'role' =>'paciente',
        ]);

        User::factory()
        ->count(50)
        ->state(['role' => 'paciente'])
        ->create();
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->whereIn('username', ['KittyKat16', 'admin'])->delete();

        $this->db->table('users')->insertBatch([
            [
                'first_name'   => 'Katherine',
                'middle_name'  => 'Olap',
                'last_name'    => 'Sinagaraw',
                'birthdate'    => '1990-07-01',
                'gender'       => 'Female',
                'email'        => 'katherine.sinagaraw@sunsonsolar.com',
                'phone_number' => '09000000000',
                'address'      => 'Pasig City',
                'department'   => 'Administration',
                'role'         => 'Admin',
                'username'     => 'KittyKat16',
                'password'     => '$2b$12$pUwwsbdtbfIjDdUHFugmbeaQWEAL2n3m8fETW4BSj69W1.8Unmkv.',
            ],
            [
                'first_name'   => 'Sol',
                'middle_name'  => 'Sun',
                'last_name'    => 'Solis',
                'birthdate'    => '1967-01-08',
                'gender'       => 'Male',
                'email'        => 'sol.solis@sunsonsolar.com',
                'phone_number' => '09000000000',
                'address'      => 'Pasig City',
                'department'   => 'IT',
                'role'         => 'Admin',
                'username'     => 'admin',
                'password'     => '$2b$12$IxQRkpJ.T0q8/ORoP.W8CucYJobBIN3J4FCM.Xrn1NbubdafGGoAG',
            ],
        ]);
    }
}

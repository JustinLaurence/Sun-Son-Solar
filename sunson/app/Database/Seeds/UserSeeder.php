<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    private const LEGACY_KAT_PASSWORD_HASH = '$2b$12$pUwwsbdtbfIjDdUHFugmbeaQWEAL2n3m8fETW4BSj69W1.8Unmkv.';

    public function run()
    {
        $accounts = [
            [
                'first_name'   => 'Katherine',
                'middle_name'  => 'Olap',
                'last_name'    => 'Sinagaraw',
                'birthdate'    => '1990-07-01',
                'gender'       => 'Female',
                'email'        => 'katherine.sinagaraw@sunsonsolar.com',
                'phone_number' => '09291230983',
                'address'      => 'Pasig City',
                'department'   => 'Administration',
                'role'         => 'admin',
                'username'     => 'KittyKat16',
                'password'     => '$2y$10$HYDSVWSyT/GxXLyQWwOfX.38UAHWp43r18IIlwu2UUoh7cznuKqzK',
                'is_active'    => 1,
                'must_change_password' => 1,
            ],
            [
                'first_name'   => 'Sol',
                'middle_name'  => 'Sun',
                'last_name'    => 'Solis',
                'birthdate'    => '1967-01-08',
                'gender'       => 'Male',
                'email'        => 'sol.solis@sunsonsolar.com',
                'phone_number' => '09291230983',
                'address'      => 'Pasig City',
                'department'   => 'IT',
                'role'         => 'admin',
                'username'     => 'admin',
                'password'     => '$2b$12$IxQRkpJ.T0q8/ORoP.W8CucYJobBIN3J4FCM.Xrn1NbubdafGGoAG',
                'is_active'    => 1,
                'must_change_password' => 1,
            ],
        ];

        foreach ($accounts as $account) {
            $existing = $this->db->table('users')->where('username', $account['username'])->get()->getRowArray();
            if ($existing) {
                $knownSeedHashes = [$account['password']];
                if ($account['username'] === 'KittyKat16') {
                    $knownSeedHashes[] = self::LEGACY_KAT_PASSWORD_HASH;
                }
                $changes = array_diff_key($account, ['password' => true, 'is_active' => true, 'must_change_password' => true]);
                if (in_array($existing['password'], $knownSeedHashes, true)) {
                    $changes['password'] = $account['password'];
                    $changes['must_change_password'] = 1;
                }
                $this->db->table('users')->where('id', $existing['id'])->update($changes);
            } else {
                $this->db->table('users')->insert($account);
            }
        }
    }
}

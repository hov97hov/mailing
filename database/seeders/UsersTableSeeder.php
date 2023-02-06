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
        $userDefaultData = [
            [
                'id' => 1,
                'name' => 'Mailing',
                'email' => 'admin@mailing.am',
                'password' => bcrypt('superadmin2023!'),
                'role' => 1,
            ],
        ];

        foreach ($userDefaultData as $userDefaultDatum) {
            User::updateOrCreate(
                [
                    'id' => $userDefaultDatum['id']
                ],
                [
                    'name' => $userDefaultDatum['name'],
                    'email' => $userDefaultDatum['email'],
                    'password' => $userDefaultDatum['password'],
                    'role' => $userDefaultDatum['role'],
                ],
            );
        }
    }
}

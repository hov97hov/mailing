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
                'name' => 'super_admin_1',
                'email' => 'superadmin12023@gmail.com',
                'password' => bcrypt('superadmin1-2023!'),
                'role' => 1,
            ],
            [
                'id' => 2,
                'name' => 'super_admin_2',
                'email' => 'superadmin22023@gmail.com',
                'password' => bcrypt('superadmin2-2023!'),
                'role' => 1,
            ]
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

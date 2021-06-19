<?php

namespace Database\Seeders;

use App\Models\StatusEntrega;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'          => 'Admin',
                'email'         => 'admin@admin.com',
                'cpf'           => '000.000.000-00',
                'valor_hora'    => 15.00,
                'password'      => password_hash('123456', PASSWORD_BCRYPT),
            ],
        ];

        User::insert($users);
    }
}

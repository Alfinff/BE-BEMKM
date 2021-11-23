<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uuid' => generateUuid(),
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('R33b00t0k3')
        ]);
    }
}

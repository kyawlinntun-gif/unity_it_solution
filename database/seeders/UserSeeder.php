<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
            'name' => "Mg Mg",
            'email' => "mgmg@gmail.com",
            'password' => Hash::make('password'),
            'is_admin' => 1
        ]);
        User::create([
            'name' => "Tun Tun",
            'email' => "tuntun@gmail.com",
            'password' => Hash::make('password')
        ]);
        User::create([
            'name' => "Hla Hla",
            'email' => "hlahla@gmail.com",
            'password' => Hash::make('password')
        ]);
    }
}

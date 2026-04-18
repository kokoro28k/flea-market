<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'name' => '山田花子',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'image' => '',
        ]);

        $user2 = User::create([
            'name' => '佐藤太郎',
            'email' => 'test2@example.com',
            'password' => Hash::make('12345678'),
            'image' => '',
        ]);

        $user3 = User::create([
            'name' => '田中次郎',
            'email' => 'test3@example.com',
            'password' => Hash::make('12345678'),
            'image' => '',
        ]);

    }
}

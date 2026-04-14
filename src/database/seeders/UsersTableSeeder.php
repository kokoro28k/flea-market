<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=[
            'name' => '山田花子',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'image' => '',
        ];
        DB::table('users') ->insert ($user);
    }
}

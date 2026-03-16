<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'=>99,
            'name'=>'Admin',
            'email'=>'jawa@jawa.com',
            'password'=>Hash::make('123123123'),
            'is_admin'=>true,
        ]);
    }
}

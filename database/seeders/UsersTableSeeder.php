<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->where('username', 'johndoe')->delete();

        DB::table('users')->insert([
            'id' => '722c0e62-755b-4c7b-b427-1b09acd85c32',
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => bcrypt('123456'),
            'role' => 1,
        ]);
    }
}

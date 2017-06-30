<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name' => str_random(10),
            'email' => 'test@test.com',
            'password' => bcrypt('123'),
            'username' => 'test',
        ]);
    }
}

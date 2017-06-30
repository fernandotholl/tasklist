<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Task::create([
            'title' => "Task test ".str_random(10),
            'status' => 1,
            'user_id' => 1,
            'description' => str_random(50),
        ]);
    }
}

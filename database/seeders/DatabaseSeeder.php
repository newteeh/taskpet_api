<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tamagotchi;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();
        User::create([
            'id' => '1',
           'email' => 'danya.pavlov.54@mail.ru',
           'password' => Hash::make('123456')
        ]);
        Tamagotchi::create([
            'user_id' => 1,
            'name' => 'testik',
            'energy' => 100,
            'hunger' => 100,
            'health_points' => 100
        ]);
        for ($i = 0; $i<=10; $i++ ){
            Task::create([
                'user_id' => 1,
                'task_name' => $faker->sentence(2) ,
                'task_date' => now(),
                'task_startTime' => now(),
                'task_endTime' => now(),
                'task_reminder' => '5 минут',
                'task_status' => 1
        ]);
        }

    }
}

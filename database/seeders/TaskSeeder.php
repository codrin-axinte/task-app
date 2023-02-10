<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Database\Factories\TaskFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user = User::first();

        Task::factory()
            ->for($user, 'user')
            ->count(10)
            ->create()
            ->each(
                fn(Task $task) => random_int(0, 100) > 50
                    ? Task::factory()
                        ->for($user, 'user')
                        ->for($task, 'parent')
                        ->count(random_int(0, 5))
                        ->create()
                    : null
            );


        Task::factory()->for($user, 'user')->completed()->count(1)->create();
    }
}

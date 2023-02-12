<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => Str::limit($this->faker->paragraph, 50, ''),
            'content' => $this->faker->paragraphs(random_int(0, 3), true),
            'due_date' => $this->faker->dateTimeBetween('yesterday', '+10 days'),
            'priority' => $this->faker->randomElement(Priority::values()),
        ];
    }

    public function completed(): TaskFactory
    {
        return $this->state([
            'completed_at' => $this->faker->dateTimeBetween('-3 days', now())
        ]);
    }

    public function deleted(): TaskFactory
    {
        return $this->state([
            'deleted_at' => now(),
        ]);
    }

}

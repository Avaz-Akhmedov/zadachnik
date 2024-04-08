<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;


class TaskFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => $this->faker->text,
            'description' => $this->faker->text,
            'status' => $this->faker->randomElement(TaskStatus::values())
        ];
    }
}

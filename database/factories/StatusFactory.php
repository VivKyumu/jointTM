<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'color' => $this->faker->hexColor,
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function defaultStatuses()
    {
        return $this->state(function (array $attributes) {
            $statuses = [
                ['name' => 'Pending', 'color' => '#ffc107', 'order' => 1],
                ['name' => 'In Progress', 'color' => '#17a2b8', 'order' => 2],
                ['name' => 'On Hold', 'color' => '#6c757d', 'order' => 3],
                ['name' => 'Completed', 'color' => '#28a745', 'order' => 4],
            ];
            
            return $this->faker->randomElement($statuses);
        });
    }
}
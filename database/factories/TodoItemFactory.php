<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\TodoItem; // Import the TodoItem model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TodoItemFactory extends Factory
{
    protected $model = TodoItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['PD', 'CD']),
            'time_started' => $this->faker->dateTimeThisYear(),
            'time_ended' => $this->faker->dateTimeThisYear(),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+1 month')
        ];
    }
}


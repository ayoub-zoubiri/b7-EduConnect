<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'teacher_id' => User::where('role', 'teacher')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'teacher'])->id,
        ];
    }
}
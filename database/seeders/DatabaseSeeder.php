<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // First create roles and permissions
        $this->call(RolePermissionSeeder::class);

        // Create admin user
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);
        $admin->assignRole('admin');

        // Create teachers
        $teachers = \App\Models\User::factory(5)->create(['role' => 'teacher']);
        $teachers->each(function ($teacher) {
            $teacher->assignRole('teacher');
        });

        // Create students
        $students = \App\Models\User::factory(20)->create(['role' => 'student']);
        $students->each(function ($student) {
            $student->assignRole('student');
        });

        // Create courses
        $courses = \App\Models\Course::factory(15)->create();

        // Enroll students in random courses
        $students->each(function ($student) use ($courses) {
            $randomCourses = $courses->random(rand(1, 5));
            foreach ($randomCourses as $course) {
                $student->enrolledCourses()->attach($course->id, [
                    'enrolled_at' => fake()->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        });
    }
}
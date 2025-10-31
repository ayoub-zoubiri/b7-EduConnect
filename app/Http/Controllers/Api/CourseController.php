<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->withCount('enrollments')->paginate(15);
        return new CourseCollection($courses);
    }

    public function store(StoreCourseRequest $request)
    {
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => $request->user()->id
        ]);

        return new CourseResource($course->load('teacher'));
    }

    public function show(Course $course)
    {
        return new CourseResource($course->load('teacher')->loadCount('enrollments'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->only(['title', 'description']));
        return new CourseResource($course->load('teacher'));
    }

    public function destroy(Course $course)
    {
        $user = request()->user();
        
        if ($user->role !== 'admin' && $course->teacher_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted']);
    }

    public function enroll(Course $course)
    {
        $user = request()->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only students can enroll'], 403);
        }

        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 409);
        }

        $user->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);

        return response()->json([
            'message' => 'Enrolled successfully',
            'course' => new CourseResource($course->load('teacher'))
        ]);
    }

    public function myEnrolledCourses()
    {
        $user = request()->user();

        if ($user->role !== 'student') {
            return response()->json(['message' => 'Only for students'], 403);
        }

        $courses = $user->enrolledCourses()
                       ->with('teacher')
                       ->withCount('enrollments')
                       ->withPivot('enrolled_at')
                       ->paginate(15);

        return new CourseCollection($courses);
    }
}
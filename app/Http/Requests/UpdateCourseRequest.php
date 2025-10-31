<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $course = $this->route('course');
        
        return $user && ($user->role === 'admin' || $course->teacher_id === $user->id);
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:5000'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 5000 caractères.'
        ];
    }
}
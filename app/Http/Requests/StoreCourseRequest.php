<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['teacher', 'admin']);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du cours est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du cours est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 5000 caractères.'
        ];
    }
}
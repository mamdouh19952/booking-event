<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatedEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'nullable|date|after:start_time',
                'available_seats' => 'required|integer|min:0',
                'category_id' => 'required|integer|exists:categories,id',
                 'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                 'images' => 'nullable|array',
                 'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:22048',
        ];
    }
}

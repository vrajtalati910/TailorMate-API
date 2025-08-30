<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'style' => 'nullable|array',
            'style.*' => 'string',
            'measurement_ids' => 'required|array',
            'measurement_ids.*' => 'integer|exists:measurements,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'The item name is required.',
            'name.string'      => 'The item name must be a string.',
            'name.max'         => 'The item name cannot exceed 255 characters.',
            'style.array'      => 'The style must be an array.',
            'style.*.string'   => 'Each style must be a string.',
            'measurement_ids.array'=> 'The measurements must be an array.',
            'measurement_ids.*.integer'=> 'The measurement id must be an integer.',
            'measurement_ids.*.exists'=> 'The measurement id does not exist.',
        ];
    }
}

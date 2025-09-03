<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:items,name,' . $this->item->id],

            // Measurements (replace all)
            'measurement_ids' => ['sometimes', 'array'],
            'measurement_ids.*' => ['integer', 'exists:measurements,id'],

            // Styles (replace all)
            'style' => ['sometimes', 'array'],
            'style.*' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Item name is required.',
            'name.string'   => 'Item name must be a valid string.',
            'name.max'      => 'Item name may not be greater than 255 characters.',
            'name.unique'   => 'Item with this name already exists.',

            'measurement_ids.array' => 'Measurement IDs must be an array.',
            'measurement_ids.*.integer' => 'Each measurement ID must be an integer.',
            'measurement_ids.*.exists' => 'One or more measurement IDs do not exist.',

            'style.array' => 'Styles must be an array.',
            'style.*.string' => 'Each style name must be a valid string.',
            'style.*.max' => 'Each style name may not be greater than 255 characters.',
        ];
    }
}

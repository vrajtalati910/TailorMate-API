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
            'name' => 'required|string|max:255',
            'style' => 'nullable|array',
            'style.*.item_id' => 'nullable|integer|exists:item_styles,id',
            'style.*.item_name' => 'required_with:style.*|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'The item name is required.',
            'name.string'                => 'The item name must be a string.',
            'name.max'                   => 'The item name cannot exceed 255 characters.',
            'style.array'                => 'The style must be an array.',
            'style.*.item_id.integer'    => 'The style ID must be an integer.',
            'style.*.item_id.exists'     => 'The style ID does not exist.',
            'style.*.item_name.required_with' => 'The style name is required when style is provided.',
            'style.*.item_name.string'   => 'The style name must be a string.',
            'style.*.item_name.max'      => 'The style name cannot exceed 255 characters.',
        ];
    }
}

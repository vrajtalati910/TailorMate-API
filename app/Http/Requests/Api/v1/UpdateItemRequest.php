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

            // Remove section
            'remove.measurements.id'   => ['sometimes', 'array'],
            'remove.measurements.id.*' => ['integer', 'exists:item_measurements,id'],
            'remove.styles.id'         => ['sometimes', 'array'],
            'remove.styles.id.*'       => ['integer', 'exists:item_styles,id'],

            // Add section
            'add.measurements.id'   => ['sometimes', 'array'],
            'add.measurements.id.*' => ['integer', 'exists:measurements,id'],
            'add.styles.name'       => ['sometimes', 'array'],
            'add.styles.name.*'     => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Item name is required.',
            'name.string'   => 'Item name must be a valid string.',
            'name.max'      => 'Item name may not be greater than 255 characters.',
            'name.unique'   => 'Item with this name already exists.',

            'remove.measurements.id.array'   => 'Measurements to remove must be an array.',
            'remove.measurements.id.*.integer' => 'Each measurement ID must be an integer.',
            'remove.measurements.id.*.exists'  => 'One or more measurements you are trying to remove do not exist.',

            'remove.styles.id.array'   => 'Styles to remove must be an array.',
            'remove.styles.id.*.integer' => 'Each style ID must be an integer.',
            'remove.styles.id.*.exists'  => 'One or more styles you are trying to remove do not exist.',

            'add.measurements.id.array'   => 'Measurements to add must be an array.',
            'add.measurements.id.*.integer' => 'Each measurement ID must be an integer.',
            'add.measurements.id.*.exists'  => 'One or more measurements you are trying to add do not exist.',

            'add.styles.name.array'   => 'Styles to add must be an array.',
            'add.styles.name.*.string' => 'Each style name must be a valid string.',
            'add.styles.name.*.max'    => 'Each style name may not be greater than 255 characters.',
        ];
    }
}

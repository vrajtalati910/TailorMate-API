<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class CustomerItemUpdateRequest extends FormRequest
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
            // Measurements (optional for update)
            'measurement' => ['sometimes', 'array'],
            'measurement.*.id' => ['required_with:measurement', 'integer', 'exists:measurements,id'],
            'measurement.*.value' => ['required_with:measurement', 'string', 'max:255'],

            // Styles (optional for update)
            'style.id'   => ['sometimes', 'array'],
            'style.id.*' => ['required_with:style.id', 'integer', 'exists:item_styles,id'],
        ];
    }

    public function messages()
    {
        return [
            'measurement.*.id.required_with' => 'Measurement ID is required when updating measurements.',
            'measurement.*.id.exists' => 'One or more selected measurements do not exist.',
            'measurement.*.value.required_with' => 'Each measurement must have a value.',
            'measurement.*.value.string' => 'Each measurement value must be text or number.',

            'style.id.*.required_with' => 'Style ID is required when updating styles.',
            'style.id.*.exists' => 'One or more selected styles do not exist.',
        ];
    }
}

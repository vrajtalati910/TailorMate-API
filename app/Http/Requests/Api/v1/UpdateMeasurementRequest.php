<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeasurementRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:measurements,name,' . $this->measurement->id
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The measurement name is required.',
            'name.string'   => 'The measurement name must be a valid string.',
            'name.max'      => 'The measurement name cannot be longer than 255 characters.',
            'name.unique'   => 'This measurement name already exists, please choose another one.',
        ];
    }
}

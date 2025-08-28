<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
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
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', 
            'name'        => 'required|string|max:100',
            'mobile'      => 'required|digits:10|unique:customers,mobile',
            'alt_mobile'  => 'nullable|digits:10|different:mobile',
            'reference'   => 'nullable|string|max:100',
            'city'        => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Customer name is required.',
            'mobile.required'     => 'Mobile number is required.',
            'mobile.digits'       => 'Mobile number must be exactly 10 digits.',
            'mobile.unique'       => 'This mobile number is already registered.',
            'alt_mobile.digits'   => 'Alternate mobile must be exactly 10 digits.',
            'alt_mobile.different'=> 'Alternate mobile must be different from primary mobile.',
            'image_path.image'    => 'The uploaded file must be an image.',
            'image_path.mimes'    => 'Only JPG, JPEG, and PNG images are allowed.',
            'image_path.max'      => 'Image size must not exceed 5MB.'
        ];
    }
}

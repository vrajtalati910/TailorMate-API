<?php

namespace App\Http\Requests\Api\v1;

use App\Models\ItemMeasurement;
use Illuminate\Foundation\Http\FormRequest;

class CustomerItemCreateRequest extends FormRequest
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
        $itemMeasurementCount = ItemMeasurement::where('item_id', $this->item_id)->get()->count();

        return [
            'item_id' => ['required', 'integer', 'exists:items,id'],

            // Measurements
            'measurement' => ['required', 'array', 'size:' . $itemMeasurementCount],
            'measurement.*.id' => ['required', 'integer', 'exists:measurements,id'],
            'measurement.*.value' => ['required', 'string', 'max:255'],
            // use numeric if it must always be a number, or regex if you expect units like "500 cm"

            // Styles
            'style.id'   => ['required', 'array'],
            'style.id.*' => ['required', 'integer', 'exists:item_styles,id'],
        ];
    }

    public function messages()
    {
        return [
            'item_id.required' => 'Item ID is required.',
            'item_id.exists'   => 'The selected item does not exist.',

            'measurement.size'=> 'All measurements for the selected item needs to be filled',
            'measurement.id.required'   => 'At least one measurement is required.',
            'measurement.id.*.exists'   => 'One or more selected measurements do not exist.',
            'measurement.value.required' => 'Each measurement must have a value.',
            'measurement.value.*.string' => 'Each measurement value must be text or number.',

            'style.id.required' => 'At least one style is needed.',
            'style.id.*.exists' => 'One or more selected styles do not exist.',
        ];
    }
}

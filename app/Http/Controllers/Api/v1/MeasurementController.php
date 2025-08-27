<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CommonListRequest;
use App\Http\Requests\Api\v1\CreateMeasurementRequest;
use App\Http\Requests\Api\v1\UpdateMeasurementRequest;
use App\Models\Measurement;

class MeasurementController extends Controller
{
    public function list(CommonListRequest $request)
    {
        $measurements = Measurement::query();

        if ($request->search) {
            $search = '%' . $request->search . '%';
            $measurements = $measurements->where('name', 'like', $search);
        }

        if ($request->has('page')) {
            return response()->json(
                collect([
                    'message' => __('messages.measurement_list_returned_successfully'),
                    'status' => '1',
                ])->merge($measurements->simplePaginate($request->has('per_page') ? $request->per_page : 10))
            );
        }

        return response()->json([
            'message' => __('messages.measurement_list_returned_successfully'),
            'data' => $measurements->get(),
            'status' => '1'
        ]);
    }

    public function create(CreateMeasurementRequest $request)
    {
        $measurement = Measurement::where('name', 'like', $request->name)->exists();

        if (!$measurement) {
            $newCreated = Measurement::create([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => __('messages.measurement_created_successfully'),
                'data' => $newCreated->refresh(),
                'status' => '1'
            ]);
        }

        return response()->json([
            'message' => __('messages.measurement_already_exists'),
            'status' => '0'
        ]);
    }

    public function update(Measurement $measurement, UpdateMeasurementRequest $request)
    {
        if ($measurement->name === $request->name) {
            return response()->json([
                'message' => __('messages.nothing_to_update'),
                'status' => '0'
            ]);
        }

        $measurement->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => __('messages.measurement_updated_successfully'),
            'data' => $measurement,
            'status' => '1'
        ]);
    }
}

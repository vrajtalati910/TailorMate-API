<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CommonListRequest;
use App\Http\Requests\Api\v1\CreateItemRequest;
use App\Http\Requests\Api\v1\UpdateItemRequest;
use App\Models\Item;
use App\Models\ItemMeasurement;
use App\Models\ItemStyle;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function list(CommonListRequest $request)
    {
        $items = Item::query();

        if ($request->search) {
            $search = '%' . $request->search . '%';
            $items = $items->where('name', 'like', $search);
        }

        if ($request->has('page')) {
            return response()->json(
                collect([
                    'message' => __('messages.item_list_returned_successfully'),
                    'status' => '1',
                ])->merge($items->simplePaginate($request->has('per_page') ? $request->per_page : 10))
            );
        }

        return response()->json([
            'message' => __('messages.item_list_returned_successfully'),
            'data' => $items->get(),
            'status' => '1'
        ]);
    }

    public function create(CreateItemRequest $request)
    {
        $item = Item::where('name', 'like', $request->name)->exists();

        if (!$item) {
            $newCreated = Item::create([
                'name' => $request->name
            ]);

            if ($request->style) {
                $styles = collect($request->style)->map(function ($name) {
                    return ['name' => $name];
                })->toArray();

                $newCreated->styles()->createMany($styles);
            }

            if ($request->has('measurement_ids')) {
                $measurements = collect($request->measurement_ids)->map(function ($id) {
                    return ['measurement_id' => $id];
                })->toArray();

                $newCreated->measurements()->attach($measurements);
            }

            return response()->json([
                'message' => __('messages.item_created_successfully'),
                'data' => $newCreated->load('styles', 'measurements'),
                'status' => '1'
            ]);
        }

        return response()->json([
            'message' => __('messages.item_already_exists'),
            'status' => '0'
        ]);
    }

    public function update(Item $item, UpdateItemRequest $request)
    {
        DB::transaction(function () use ($item, $request) {
            // 1. Update name if changed
            if ($item->name !== $request->name) {
                $item->update(['name' => $request->name]);
            }

            // 2. Handle removing measurements
            if ($request->has('remove.measurements.id')) {
                $removeMeasurementIds = $request->input('remove.measurements.id', []);
                ItemMeasurement::whereIn('id', $removeMeasurementIds)->delete();
            }

            // 3. Handle adding measurements
            if ($request->has('add.measurements.id')) {
                $addMeasurementIds = $request->input('add.measurements.id', []);
                $item->measurements()->attach($addMeasurementIds);
            }

            // 4. Handle removing styles
            if ($request->has('remove.styles.id')) {
                $removeStyleIds = $request->input('remove.styles.id', []);
                ItemStyle::whereIn('id', $removeStyleIds)->delete();
            }

            // 5. Handle adding styles
            if ($request->has('add.styles.name')) {
                $addStyleNames = $request->input('add.styles.name', []);
                foreach ($addStyleNames as $styleName) {
                    $item->styles()->create(['name' => $styleName]);
                }    
            }
        });

        return response()->json([
            'message' => __('messages.item_updated_successfully'),
            'data'    => $item->load('styles', 'measurements'),
            'status'  => '1'
        ]);
    }

    public function show(Item $item)
    {
        $item = $item->load('measurements', 'styles');

        return response()->json([
            'message' => __('messages.item_details_returned_successfully'),
            'data' => $item,
            'status' => '1'
        ]);
    }
}

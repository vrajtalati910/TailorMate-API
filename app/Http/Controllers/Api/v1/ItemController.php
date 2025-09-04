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
                    'status' => 1,
                ])->merge($items->simplePaginate($request->has('per_page') ? $request->per_page : 10))
            );
        }

        return response()->json([
            'message' => __('messages.item_list_returned_successfully'),
            'data' => $items->get(),
            'status' => 1
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
                'status' => 1
            ]);
        }

        return response()->json([
            'message' => __('messages.item_already_exists'),
            'status' => 0
        ]);
    }

    public function update(Item $item, UpdateItemRequest $request)
    {
        try {
            $result = DB::transaction(function () use ($item, $request) {
                $hasChanges = false;

                // Update name if changed
                if ($item->name !== $request->name) {
                    $item->update(['name' => $request->name]);
                    $hasChanges = true;
                }

                // Replace measurements if provided
                if ($request->has('measurement_ids')) {
                    // Delete existing measurements
                    ItemMeasurement::where('item_id', $item->id)->delete();
                    
                    // Create new measurements
                    $measurementIds = $request->input('measurement_ids');
                    $measurements = collect($measurementIds)->map(function ($id) {
                        return ['measurement_id' => $id];
                    })->toArray();
                    
                    $item->measurements()->attach($measurements);
                    $hasChanges = true;
                }

                // Replace styles if provided
                if ($request->has('style')) {
                    // Delete existing styles
                    ItemStyle::where('item_id', $item->id)->delete();
                    
                    // Create new styles
                    $styleNames = $request->input('style');
                    $styles = collect($styleNames)->map(function ($name) {
                        return ['name' => $name];
                    })->toArray();
                    
                    $item->styles()->createMany($styles);
                    $hasChanges = true;
                }

                // Check if any changes were made
                if (!$hasChanges) {
                    return [
                        'message' => __('messages.nothing_to_update'),
                        'status' => 0
                    ];
                }

                return [
                    'message' => __('messages.item_updated_successfully'),
                    'data' => $item->load('styles', 'measurements'),
                    'status' => 1
                ];
            });

            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => __('messages.item_update_failed'),
                'status' => 0
            ]);
        }
    }

    public function show(Item $item)
    {
        $item = $item->load('measurements', 'styles');

        return response()->json([
            'message' => __('messages.item_details_returned_successfully'),
            'data' => $item,
            'status' => 1
        ]);
    }
}

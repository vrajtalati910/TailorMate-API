<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CommonListRequest;
use App\Http\Requests\Api\v1\CreateItemRequest;
use App\Http\Requests\Api\v1\UpdateItemRequest;
use App\Models\Item;
use App\Models\ItemStyle;

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

            return response()->json([
                'message' => __('messages.item_created_successfully'),
                'data' => $newCreated->load('styles'),
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
        $hasChanges = false;

        // Update item name if changed
        if ($item->name !== $request->name) {
            $item->update([
                'name' => $request->name
            ]);
            $hasChanges = true;
        }

        // Handle styles update
        if ($request->has('style') && is_array($request->style)) {
            foreach ($request->style as $styleData) {
                if (isset($styleData['item_id']) && $styleData['item_id']) {
                    // Update existing style
                    $existingStyle = ItemStyle::find($styleData['item_id']);
                    if ($existingStyle && $existingStyle->item_id === $item->id) {
                        $existingStyle->update([
                            'name' => $styleData['item_name']
                        ]);
                        $hasChanges = true;
                    }
                } else {
                    // Create new style
                    $item->styles()->create([
                        'name' => $styleData['item_name']
                    ]);
                    $hasChanges = true;
                }
            }
        }

        if (!$hasChanges) {
            return response()->json([
                'message' => __('messages.nothing_to_update'),
                'status' => '0'
            ]);
        }

        return response()->json([
            'message' => __('messages.item_updated_successfully'),
            'data' => $item->load('styles'),
            'status' => '1'
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

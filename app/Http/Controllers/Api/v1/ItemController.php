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
                $style = collect($request->style)->map(function ($name) {
                    return ['name' => $name];
                })->toArray();

                $newCreated->styles()->createMany($style);
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
        if ($item->name === $request->name) {
            return response()->json([
                'message' => __('messages.nothing_to_update'),
                'status' => '0'
            ]);
        }

        $item->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => __('messages.item_updated_successfully'),
            'data' => $item,
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

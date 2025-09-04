<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CommonListRequest;
use App\Http\Requests\Api\v1\CustomerCreateRequest;
use App\Http\Requests\Api\v1\CustomerItemCreateRequest;
use App\Http\Requests\Api\v1\CustomerItemUpdateRequest;
use App\Http\Requests\Api\v1\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerItem;
use App\Models\CustomerItemMeasurement;
use App\Models\CustomerItemStyle;
use App\Models\Item;
use App\Models\ItemMeasurement;
use App\Traits\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    use FileManager;
    public function list(CommonListRequest $request)
    {
        $customers = Customer::query();

        if ($request->search) {
            $search = '%' . $request->search . '%';
            $customers = $customers->where('name', 'like', $search)
                ->orWhere('mobile', 'like', $search)
                ->orWhere('alt_mobile', 'like', $search)
                ->orWhere('city', 'like', $search)
                ->orWhere('reference', 'like', $search);
        }

        if ($request->has('page')) {
            return response()->json(
                collect([
                    'message' => __('messages.customer_list_returned_successfully'),
                    'status' => 1,
                ])->merge($customers->simplePaginate($request->has('per_page') ? $request->per_page : 10))
            );
        }

        return response()->json([
            'message' => __('messages.customer_list_returned_successfully'),
            'data' => $customers->get(),
            'status' => 1
        ]);
    }

    public function create(CustomerCreateRequest $request)
    {

        try {
            if ($request->has('image')) {
                $imagePath = $this->saveFile($request->image, 'customers', $request->is_from_web, $request->extension);
            }

            $customer = Customer::create([
                'image_path'  => $imagePath ?? null,
                'name'        => $request->name,
                'mobile'      => $request->mobile,
                'alt_mobile'  => $request->alt_mobile,
                'reference'   => $request->reference,
                'city'        => $request->city,
            ]);

            return response()->json([
                'message' => __('messages.customer_created_successfully'),
                'data' => $customer->refresh(),
                'status' => 1
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_creation_failed'),
                'status' => 0
            ]);
        }
    }

    public function update(Customer $customer, CustomerUpdateRequest $request)
    {
        try {
            // Handle image update
            if ($request->hasFile('image')) {
                // Delete existing image if it exists
                if ($customer->image_path) {
                    $this->deleteFile($customer->image_path);
                }

                // Save new image
                $imagePath = $this->saveFile($request->image, 'customers', $request->is_from_web ?? false, $request->extension ?? 'png');
                $customer->image_path = $imagePath;
            }

            // Handle other fields - only update if present in request
            if ($request->has('name')) {
                $customer->name = $request->name;
            }

            if ($request->has('mobile')) {
                $customer->mobile = $request->mobile;
            }

            if ($request->has('alt_mobile')) {
                $customer->alt_mobile = $request->alt_mobile;
            }

            if ($request->has('reference')) {
                $customer->reference = $request->reference;
            }

            if ($request->has('city')) {
                $customer->city = $request->city;
            }

            // Check if there are any changes to update
            if (!$customer->isDirty()) {
                return response()->json([
                    'message' => __('messages.nothing_to_update'),
                    'status' => 0
                ]);
            }

            // Save the customer
            $customer->save();

            return response()->json([
                'message' => __('messages.customer_updated_successfully'),
                'data' => $customer,
                'status' => 1
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_update_failed'),
                'status' => 0
            ]);
        }
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'message' => __('messages.customer_details_returned_successfully'),
            'data' => $customer->load('customerItems'),
            'status' => 1
        ]);
    }

    public function addItem(Customer $customer, CustomerItemCreateRequest $request)
    {
        try {
            $result = DB::transaction(function () use ($customer, $request) {
                $measurementIdsAndValues = $request->input('measurement');
                $styleIds = $request->input('style.id');

                $customerItem = CustomerItem::create([
                    'customer_id' => $customer->id,
                    'item_id' => $request->item_id,
                ]);

                foreach ($styleIds as $styleId) {
                    CustomerItemStyle::create([
                        'customer_item_id' => $customerItem->id,
                        'item_style_id' => $styleId,
                    ]);
                }

                foreach ($measurementIdsAndValues as $measurementIdAndValue) {
                    CustomerItemMeasurement::create([
                        'customer_item_id' => $customerItem->id,
                        'measurement_id' => $measurementIdAndValue['id'],
                        'value' => $measurementIdAndValue['value'],
                    ]);
                }

                return [
                    'message' => __('messages.customer_item_created_successfully'),
                    'data' => $customerItem->load('measurements', 'styles'),
                    'status' => 1
                ];
            });

            return response()->json($result);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_item_creation_failed'),
                'status' => 1
            ]);
        }
    }

    public function updateItem(CustomerItem $customerItems, CustomerItemUpdateRequest $request)
    {
        try {
            $result = DB::transaction(function () use ($customerItems, $request) {
                $hasChanges = false;

                // Update measurements if provided
                if ($request->has('measurement')) {
                    $measurementIdsAndValues = $request->input('measurement');
                    
                    // Delete existing measurements
                    CustomerItemMeasurement::where('customer_item_id', $customerItems->id)->delete();
                    
                    // Create new measurements
                    foreach ($measurementIdsAndValues as $measurementIdAndValue) {
                        CustomerItemMeasurement::create([
                            'customer_item_id' => $customerItems->id,
                            'measurement_id' => $measurementIdAndValue['id'],
                            'value' => $measurementIdAndValue['value'],
                        ]);
                    }
                    $hasChanges = true;
                }

                // Update styles if provided
                if ($request->has('style.id')) {
                    $styleIds = $request->input('style.id');
                    
                    // Delete existing styles
                    CustomerItemStyle::where('customer_item_id', $customerItems->id)->delete();
                    
                    // Create new styles
                    foreach ($styleIds as $styleId) {
                        CustomerItemStyle::create([
                            'customer_item_id' => $customerItems->id,
                            'item_style_id' => $styleId,
                        ]);
                    }
                    $hasChanges = true;
                }

                // Check if any changes were made
                if (!$hasChanges) {
                    return [
                        'message' => __('messages.nothing_to_update'),
                        'status' => 0
                    ];
                }

                
                $customerItems->item_id = (string) $customerItems->item_id;

                return [
                    'message' => __('messages.customer_item_updated_successfully'),
                    'data' => $customerItems->load('measurementRecords', 'styleRecords'),
                    'status' => 1
                ];
            });

            return response()->json($result);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_item_update_failed'),
                'status' => 0
            ]);
        }
    }

    public function itemsDetails(CustomerItem $customerItems)
    {
        return response()->json([
            'message' => __('messages.customer_item_details_fetched'),
            'data' => $customerItems->load('measurementRecords', 'styleRecords'),
            'status' => 1
        ]);
    }
}

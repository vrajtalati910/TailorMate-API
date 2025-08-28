<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CommonListRequest;
use App\Http\Requests\Api\v1\CustomerCreateRequest;
use App\Http\Requests\Api\v1\CustomerUpdateRequest;
use App\Models\Customer;
use App\Traits\FileManager;
use Illuminate\Http\Request;
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
                ->orWhere('refrence', 'like', $search);
        }

        if ($request->has('page')) {
            return response()->json(
                collect([
                    'message' => __('messages.customer_list_returned_successfully'),
                    'status' => '1',
                ])->merge($customers->simplePaginate($request->has('per_page') ? $request->per_page : 10))
            );
        }

        return response()->json([
            'message' => __('messages.customer_list_returned_successfully'),
            'data' => $customers->get(),
            'status' => '1'
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
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_creation_failed'),
                'status' => '0'
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
                    'status' => '0'
                ]);
            }

            // Save the customer
            $customer->save();

            return response()->json([
                'message' => __('messages.customer_updated_successfully'),
                'data' => $customer,
                'status' => '1'
            ]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => __('messages.customer_update_failed'),
                'status' => '0'
            ]);
        }
    }
}

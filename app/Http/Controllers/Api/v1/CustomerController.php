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
        if ($customer->name === $request->name) {
            return response()->json([
                'message' => __('messages.nothing_to_update'),
                'status' => '0'
            ]);
        }

        if ($request->has('image')) {
            $imagePath = $this->saveFile($request->image, 'customers', $request->is_from_web, $request->extension);
        }

        $customer->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => __('messages.measurement_updated_successfully'),
            'data' => $customer,
            'status' => '1'
        ]);
    }
}

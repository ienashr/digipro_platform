<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Actions\Options\GetCustomerOptions;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderListResource;
use App\Http\Resources\Order\SubmitOrderResource;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService, GetCustomerOptions $getCustomerOptions)
    {
        $this->orderService = $orderService;
        $this->getCustomerOptions = $getCustomerOptions;
    }

    public function index()
    {
        return Inertia::render('admin/order/index', [
            "title" => 'Digipro | Order',
            "additional" => [
                'customer_list' => $this->getCustomerOptions->handle()
            ]
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->orderService->getData($request);

            $result = new OrderListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(CreateOrderRequest $request)
    {
        try {
            $data = $this->orderService->createData($request);

            $result = new SubmitOrderResource($data, 'Success Create Order');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData($id, UpdateOrderRequest $request)
    {
        try {
            $data = $this->orderService->updateData($id, $request);

            $result = new SubmitOrderResource($data, 'Success Update Order');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id)
    {
        try {
            $data = $this->orderService->deleteData($id);

            $result = new SubmitOrderResource($data, 'Success Delete Order');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
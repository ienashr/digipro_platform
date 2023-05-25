<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Actions\Options\GetCustomerOptions;
use App\Actions\Options\GetProductOptions;
use App\Http\Requests\Order\AddOrderItemRequest;
use App\Http\Requests\Order\UpdateOrderItemRequest;
use App\Http\Resources\Order\OrderItemListResource;
use App\Http\Resources\Order\SubmitOrderItemResource;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderListResource;
use App\Http\Resources\Order\SubmitOrderResource;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService, GetCustomerOptions $getCustomerOptions, GetProductOptions $getProductOptions)
    {
        $this->orderService = $orderService;
        $this->getCustomerOptions = $getCustomerOptions;
        $this->getProductOptions = $getProductOptions;
    }

    public function index()
    {
        return Inertia::render('admin/order/index', [
            "title" => 'Digipro | Order',
            "additional" => [
                'customer_list' => $this->getCustomerOptions->handle(),
                'product_list' => $this->getProductOptions->handle(),
            ]
        ]);
    }

    public function getOrderItemData(Request $request)
    {
        try {
            $data = $this->orderService->getOrderItemData($request);

            $result = new OrderItemListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function getProductData(Request $request)
    {
        try {
            $data = $this->orderService->getProductData($request);
            return $data;
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function addOrderItem(AddOrderItemRequest $request)
    {
        try {
            $data = $this->orderService->addProductToOrderItem($request);

            $result = new SubmitOrderItemResource($data, 'Success Add Product to Order Item');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateQtyOrderItem($id, UpdateOrderItemRequest $request)
    {
        try {
            $data = $this->orderService->updateQtyProductOrderItem($id, $request);

            $result = new SubmitOrderItemResource($data, 'Success Update Qty Product');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteFromOrderItem($id)
    {
        try {
            $data = $this->orderService->deleteProductFromOrderItem($id);

            $result = new SubmitOrderItemResource($data, 'Success Delete Product From Order Item');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
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
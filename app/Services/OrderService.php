<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function getData($request)
    {
        $search = $request->search;
        $filter_customer = $request->filter_customer;


        $query = Order::query();

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('customer_email', 'like', '%' . $search . '%');
        });
        $query->when(request('filter_customer', false), function ($q) use ($filter_customer) {
            $q->where('customer_id', $filter_customer);
        });

        return $query->paginate(10);
    }

    public function createData($request)
    {
        $inputs = $request->only(['customer_email', 'status', 'total_price', 'payment_status', 'customer_id']);
        $order = Order::create($inputs);

        return $order;
    }

    public function deleteData($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return $order;
    }

    public function updateData($id, $request)
    {
        $order = Order::findOrFail($id);
        
        $inputs = $request->only(['customer_email', 'status', 'total_price', 'payment_status', 'customer_id']);
        
        $order->update($inputs);

        return $order;
    }
}
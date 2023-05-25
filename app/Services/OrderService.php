<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;

class OrderService
{

    public function getOrderItemData()
    {
        $query = OrderItem::with(['product']);
        return $query->get();
    }

    public function getProductData($request)
    {
        $category_id = $request->category_id;

        $query = Product::query();

        // Filtering data
        $query->when(request('category_id', false), function ($q) use ($category_id) {
            $q->where('category_id', $category_id);
        });

        return $query->pluck('name', 'id');
    }

    public function addProductToOrderItem($request)
    {
        $inputs = $request->only(['product_id', 'qty']);
        $inputs['customer_id'] = $request->customer_id;

        // Calculating Qty if Cart Exists
        $order_item = OrderItem::where('product_id', $request->product_id)->where('customer_id', $inputs['customer_id'])->first();
        $order_item ? $inputs['qty'] += $order_item->qty : $inputs['qty'];

        // Check Product Stock
        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $inputs['qty']) {
            throw new \Exception('Out of stock product');
        }

        // Calculate price and insert it to cart table
        $inputs['price'] = $product->price * $inputs['qty'];
        $order_items = OrderItem::updateOrCreate([
            'product_id' => $request->product_id,
            'customer_id' => $inputs['customer_id'],
        ], $inputs);
        return $order_items;
    }

    public function updateQtyProductOrderItem($id, $request)
    {
        $inputs = $request->only(['qty']);
        $order_item = OrderItem::findOrFail($id);

        // Check Product Stock
        $product = Product::findOrFail($order_item->product_id);
        if ($product->stock < $inputs['qty']) {
            throw new \Exception('Out of stock product');
        }

        $inputs['price'] = $product->price * $inputs['qty'];
        $order_item->update($inputs);
        return $order_item;
    }

    public function deleteProductFromOrderItem($id)
    {
        $order_item = OrderItem::findOrFail($id);
        $order_item->delete();

        return $order_item;
    }



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
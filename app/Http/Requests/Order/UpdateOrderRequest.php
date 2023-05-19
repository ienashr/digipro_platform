<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_email' => 'required|string',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required',
        ];
    }
}
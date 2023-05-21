<?php


namespace App\Actions\Options;
use App\Models\Product;


class GetProductOptions
{
   public function handle()
   {
       $customer = Product::pluck('name','id');
       return $customer;
   }
}
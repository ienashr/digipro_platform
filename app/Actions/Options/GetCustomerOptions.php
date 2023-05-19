<?php


namespace App\Actions\Options;
use App\Models\Customer;


class GetCustomerOptions
{
   public function handle()
   {
       $customer = Customer::pluck('name','id');


       return $customer;
   }
}
<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Telephone;

class CustomerRepository implements CustomerRepositoryInterface {

    public function all()
    {
        return Customer::latest()->get();
    }

    public function create($input)
    {
        $customer = new Customer();
        $customer->name = $input['name'];
        $customer->nic = $input['nic'];
        $customer->save();

        return $customer;
    }

    public function update($input,$id)
    {
        $customer = Customer::findOrFail($id);
        $customer->name = $input['name'];
        $customer->nic = $input['nic'];
        $customer->save();

        return $customer;
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return $customer;
    }

    public function search($input)
    {
        $nic = Customer::where('nic','LIKE',"%{$input->terms}%");
        $address = Address::where('address','LIKE',"%{$input->terms}%");
        $telephone = Telephone::where('telephone','LIKE',"%{$input->terms}%");
        $customer = Customer::where("name","LIKE","%{$input->terms}%")->union($nic)->union($address)->union($telephone)->get();

        return $customer;
    }
}

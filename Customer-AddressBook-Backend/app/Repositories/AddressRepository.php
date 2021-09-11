<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository implements AddressRepositoryInterface {

    public function all()
    {
        return Address::get();
    }

    public function create($input)
    {
        $address = new Address();
        $address->address = $input['address'];
        $address->customer_id = $input['customer_id'];
        $address->save();

        return $address;
    }

    public function update($input,$id)
    {
        $address = Address::where('customer_id',$id)->first();
        $address->address = $input['address'];
        $address->customer_id = $id;
        $address->save();

        return $address;
    }

    public function delete($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return $address;
    }
}

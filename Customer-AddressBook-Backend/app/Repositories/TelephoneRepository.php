<?php

namespace App\Repositories;

use App\Models\Telephone;

class TelephoneRepository implements TelephoneRepositoryInterface {

    public function all()
    {
        return Telephone::get();
    }

    public function create($input)
    {
        $telephone = new Telephone();
        $telephone->telephone = $input['telephone']['telephoneNumber'];
        $telephone->customer_id = $input['customer_id'];
        $telephone->save();

        return $telephone;
    }

    public function update($input,$id)
    {
        $telephone = Telephone::findOrFail($id);
        $telephone->telephone = $input['telephone'];
        $telephone->customer_id = $input['customer_id'];
        $telephone->save();

        return $telephone;
    }

    public function delete($id)
    {
        $telephone = Telephone::where('customer_id',$id)->get();
        $telephone->delete();

        return $telephone;
    }
}

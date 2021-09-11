<?php

namespace App\Repositories;

interface TelephoneRepositoryInterface
{
    public function all();

    public function create($input);

    public function update($input, $id);

    public function delete($id);
}

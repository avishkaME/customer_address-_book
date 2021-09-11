<?php

namespace App\Repositories;

interface CustomerRepositoryInterface
{
    public function all();

    public function create($input);

    public function update($input, $id);

    public function delete($id);

    public function search($input);
}

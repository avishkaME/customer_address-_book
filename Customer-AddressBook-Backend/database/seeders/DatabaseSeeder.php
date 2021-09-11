<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Telephone;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory(20)->create()->each(function($customer) {
            Address::factory(1)->create([
                'customer_id' => $customer->id
            ]);
            Telephone::factory(rand(1,4))->create([
                'customer_id' => $customer->id
            ]);
        });
    }
}

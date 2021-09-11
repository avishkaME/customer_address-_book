<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Repositories\AddressRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\TelephoneRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customerRepository;
    private $addressRepository;
    private $telephoneRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository, AddressRepositoryInterface $addressRepository, TelephoneRepositoryInterface $telephoneRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->telephoneRepository = $telephoneRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customerRepository->all();

        return CustomerResource::collection($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->create($input);

            if ($customer) {
                $input['customer_id'] = $customer->id;
                $address = $this->addressRepository->create($input);
                $customer->address = $address;

                $telephones = array();

                foreach ($input['telephones'] as $telephone) {
                    $input['telephone'] = $telephone;
                    $telephone = $this->telephoneRepository->create($input);
                    array_push($telephones, $telephone);
                }
                $customer->telephones = $telephones;
            }
            DB::commit();
            return response()->json([
                'message' => 'Customer Saved Successfully!',
                'data' => $customer,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->update($input, $id);

            if ($input['address']) {
                $address = $this->addressRepository->update($input, $id);
                $customer->address = $address;
            }

            if ($input['telephones']) {
                $telephones = array();

                foreach ($input['telephones'] as $telephone) {
                    $input['telephone'] = $telephone['number'];
                    $telephone = $this->telephoneRepository->update($input, $telephone['id']);
                    array_push($telephones, $telephone);
                }
                $customer->telephones = $telephones;
            }

            DB::commit();

            return response()->json([
                'message' => 'Customer Updated Successfully!',
                'data' => $customer
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customerRepository->delete($id);

        return response()->json('Customer Deleted Successfully!', 200);
    }

    public function search(Request $request)
    {
        $customers = $this->customerRepository->search($request);

        return CustomerResource::collection($customers);
    }
}

<?php

namespace App\Http\Controllers\Address;

use App\Http\Requests\Address\AddressStoreRequest;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
        return AddressResource::collection(
            $request->user()->addresses
        );
    }

    public function store(AddressStoreRequest $request)
    {
        $address = Address::make($request->only(
            [
                'name', 'address_1', 'city', 'postal_code', 'country_id', 'default'
            ]
        ));

        $request->user()->addresses()->save($address);

        return new AddressResource($address);
    }

    public function update(Request $request, $address_id)
    {
        $address = $request->user()->addresses()->find($address_id);
        if(!$address){
            return response()->json([], 404);
        }

        $address->switchToDefault($address_id);

        return AddressResource::collection($request->user()->addresses);

    }

    public function destroy(Request $request, $address_id)
    {
        $address = $request->user()->addresses()->find($address_id);
        if(!$address){
            return response()->json([], 404);
        }
        $address->deleteAddress($address_id);
        return AddressResource::collection($request->user()->addresses);
    }
}

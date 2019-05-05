<?php

namespace App\Http\Controllers\Address;

use App\Http\Resources\Address\ShippingMethodResource;
use App\Models\Address;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function action(Address $address)
    {
        $this->authorize('show', $address);
        return ShippingMethodResource::collection($address->country->shipping_methods);
    }
}

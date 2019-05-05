<?php

namespace App\Http\Controllers\Country;

use App\Http\Resources\Address\CountryResource;
use App\Models\Country;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        return CountryResource::collection(
            Country::all()
        );
    }
}

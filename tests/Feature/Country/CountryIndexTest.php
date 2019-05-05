<?php

namespace Tests\Feature\Country;

use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
    public function test_it_returns_a_collection_of_countries()
    {
        $user = factory(User::class)->create();

        $country = factory(Country::class)->create();

        $this->jsonAs($user, 'GET', '/api/countries')
                ->assertJsonFragment([
                    'name' => $country->name
                ]);
    }
}

<?php

namespace Tests\Unit\Models\Address;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_it_belongs_to_a_user()
    {
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(User::class, $address->user);
    }

    public function test_it_has_one_country()
    {
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(Country::class, $address->country);
    }

    public function test_it_update_all_other_addresses_as_not_default_when_creating_new_default_address()
    {
        $user = factory(User::class)->create();
        $old_default_address = factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $this->assertFalse($old_default_address->fresh()->default);
    }
}

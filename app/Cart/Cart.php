<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching($this->getStorePayload($products));
    }

    public function update($product_id, $quantity)
    {
        $this->user->cart()->updateExistingPivot($product_id, [
            'quantity' => $quantity
        ]);
    }

    public function delete($product_id)
    {
        $this->user->cart()->detach($product_id);
    }

    public function empty()
    {
        $this->user->cart()->detach();
    }

    private function getStorePayload($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        })->toArray();
    }

    private function getCurrentQuantity($product_id)
    {
        if ($product = $this->user->cart->where('id', $product_id)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }
}

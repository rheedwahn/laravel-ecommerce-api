<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected $user;

    protected $changed = false;

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

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    public function subTotal()
    {
        $subtotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($subtotal);
    }

    public function total()
    {
        return $this->subTotal();
    }

    public function syncCart()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);
            $this->changed = $quantity != $product->pivot->quantity;
            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    public function hasChanged()
    {
        return $this->changed;
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

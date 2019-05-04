<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuantityRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request, Cart $cart)
    {
        $cart->syncCart();
        $request->user()->load(['cart.product', 'cart.product.variations.stock', 'cart.stock', 'cart.type']);
        return (new CartResource($request->user()))
            ->additional([
                'meta' => $this->meta($cart)
            ]);
    }

    private function meta($cart)
    {
        return [
            'empty' => $cart->isEmpty(),
            'sub_total' => $cart->subTotal()->formatted(),
            'total' => $cart->total()->formatted(),
            'changed' => $cart->hasChanged()
        ];
    }

    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->add($request->products);
    }

    public function update(ProductVariation $productVariation, UpdateQuantityRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }

    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation->id);
    }
}

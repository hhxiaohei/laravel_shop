<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

/**
 * Class CartService
 * @package App\Services
 */
class CartService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return Auth::user()->cartItems()->with([
            'productSku.product',
        ])->get();
    }

    /**
     * @param $skuId
     * @param $amount
     * @return CartItem|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     */
    public function add($skuId, $amount)
    {
        $user = Auth::user();
        if ($item = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            $item->update([
                'amount' => $item->amout + $amount,
            ]);
        } else {
            $item = new CartItem([
                'amount' => $amount,
            ]);
            $item->user()->associate($user);
            $item->productSku()->associate($skuId);
            $item->save();
        }
        return $item;
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function remove($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];

        return Auth::user()->cartItems()->whereIn('product_sku_id', $ids)->delete();
    }
}
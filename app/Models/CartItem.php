<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $user_id 关联用户ID
 * @property int $product_sku_id sku id
 * @property int $amount 商品数量
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ProductSku $productSku
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereProductSkuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereUserId($value)
 * @mixin \Eloquent
 */
class CartItem extends Model
{
    protected $fillable = ['amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}

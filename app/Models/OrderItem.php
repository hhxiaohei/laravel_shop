<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $order_id order_id
 * @property int $product_id product_id
 * @property int $product_sku_id
 * @property int $amount 数量
 * @property float $price 单价
 * @property int $rating 用户打分
 * @property string|null $review review
 * @property \Carbon\Carbon|null $reviewed_at 评价时间
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductSku $product_sku
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereProductSkuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\ProductSku $productSku
 */
class OrderItem extends Model
{
    protected $guarded = [];
    protected $dates = ['reviewed_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}

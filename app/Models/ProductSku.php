<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductSku
 *
 * @property int $id
 * @property string $title SKU 名称
 * @property string $description SKU 描述
 * @property float $price SKU 价格
 * @property int $stock
 * @property int $product_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartItem[] $cartItem
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductSku extends Model
{
    protected $fillable = [
        'id',
        'description',
        'title',
        'price',
        'stock',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItem()
    {
        return $this->hasMany(CartItem::class);
    }

    //减库存
    public function decrementStock($amount)
    {
        if($amount<0)
            throw new InternalException('减库存不可小于0');

        //查询构造器 返回操作成功行数
        return $this
            ->newQuery()
            ->where('id' , $this->id)
            ->where('stock','>=' , $amount)
            ->decrement('stock',$amount);
    }

    //加库存
    public function incrementStork($amount)
    {
        if($amount < 0)
            throw new InternalException('加库存不能小于0');

        return $this->newQuery()->increment('stock' , $amount);
    }
}

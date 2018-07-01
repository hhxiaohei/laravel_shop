<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];
    protected $dates = ['reviewed_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product_sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}

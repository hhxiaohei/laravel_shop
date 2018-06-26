<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

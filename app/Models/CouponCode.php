<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    protected $guarded = [];

    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $TYPE_MAP = [
        self::TYPE_FIXED   => 'fixed',
        self::TYPE_PERCENT => 'fixed',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $dates = [
        'not_before',
        'not_after',
    ];

    public static function findAvailableCode($length = 16)
    {
        do{
            $code = strtoupper(str_random($length));
        }while(self::query()->whereCode($code)->exists());
        return $code;
    }

    protected $appends = ['description'];

    public function getDescriptionattribute()
    {
        $str = '';

        if($this->min_amount > 0){
            $str = '满' . $this->min_amount;
        }

        if($this->type == self::TYPE_PERCENT){
            return $str.'优惠'.number_format($this->value,0).'%';
        }

        return $str.'减'.$this->value;
    }
}

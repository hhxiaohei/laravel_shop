<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    //退款状态
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_APPLIED = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    //发货状态
    const SHIP_STS_PENDING = 'pending';
    const SHIP_STS_DELIVERED = 'delivered';
    const SHIP_STS_RECEIVED = 'received';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款',
        self::REFUND_STATUS_APPLIED    => '已申请退款',
        self::REFUND_STATUS_PROCESSING => '退款中',
        self::REFUND_STATUS_SUCCESS    => '退款成功',
        self::REFUND_STATUS_FAILED     => '退款失败',
    ];

    public static $shipStatusMap = [
        self::SHIP_STS_PENDING   => '未发货',
        self::SHIP_STS_DELIVERED => '已发货',
        self::SHIP_STS_RECEIVED  => '已收货',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'reviewed'  => 'boolean',
        'address'   => 'json',
        'ship_data' => 'json',
        'extra'     => 'json',
    ];

    protected $dates = ['paid_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            //创建流水号
            $model->mo = static::findAvailableNo();
            if(!$model->no){
                return false;
            }
        });
    }

    //创建订单号
    public static function findAvailableNo()
    {
        $order_prefix = date('YmdHis');
        //循环是为了尽可能提高订单号生成的成功几率
        for ($i=0;$i<10;$i++){
            //随机生成 6位数字 (前缀 + 随机数字 (左填充0))
            $no = $order_prefix . str_pad(random_int(0,999999) , 6,'0',STR_PAD_LEFT);

            //不存在订单号
            if(!static::query()->where('no' , $no)->exists()){
                return $no;
            }
        }
        \Log::error('生成订单号失败');

        return false;
    }
    
    
}

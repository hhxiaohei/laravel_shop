<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $no 订单号
 * @property int $user_id 下单user_id
 * @property array $address 地址存json
 * @property float $total_amount 订单总金额
 * @property string $note 订单备注
 * @property \Carbon\Carbon|null $paid_at 支付时间
 * @property string|null $payment_method 支付方式
 * @property string|null $payment_no 支付平台订单号
 * @property string|null $refund_status 退款状态
 * @property string|null $refund_no 退款单号
 * @property bool $closed 订单是否关闭
 * @property bool $reviewed 订单是否评价
 * @property string|null $ship_status 物流状态
 * @property array $ship_data 物流数据
 * @property array $extra 额外数据
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereRefundNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereRefundStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShipData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 */
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


    //支付类型
    const PAYMENT_METHOD_ALIPAY = 'alipay';
    const PAYMENT_METHOD_WECHAT = 'wechat_pay';

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
            $model->no = static::findAvailableNo();
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

    //生成退款单号
    public static function getAvailableRefundNo()
    {
        do{
            $no = Uuid::uuid4()->getHex();
        }while(
            self::query()->where('refund_no',$no)->exists()
        );

        return $no;
    }
    
    
}

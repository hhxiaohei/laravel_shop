@extends('layouts.app')
@section('title', '查看订单')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>订单详情</h4>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>商品信息</th>
                            <th class="text-center">单价</th>
                            <th class="text-center">数量</th>
                            <th class="text-right item-amount">小计</th>
                        </tr>
                        </thead>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td class="product-info">
                                    <div class="preview">
                                        <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                                            <img src="{{ $item->product->image }}">
                                        </a>
                                    </div>
                                    <div>
            <span class="product-title">
               <a target="_blank"
                  href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
             </span>
                                        <span class="sku-title">{{ $item->productSku->title }}</span>
                                    </div>
                                </td>
                                <td class="sku-price text-center vertical-middle">￥{{ $item->price }}</td>
                                <td class="sku-amount text-center vertical-middle">{{ $item->amount }}</td>
                                <td class="item-amount text-right vertical-middle">
                                    ￥{{ number_format($item->price * $item->amount, 2, '.', '') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                    </table>
                    <div class="order-bottom">
                        <div class="order-info">
                            <div class="line">
                                <div class="line-label">收货地址：</div>
                                <div class="line-value">{{ join(' ', $order->address) }}</div>
                            </div>
                            <div class="line">
                                <div class="line-label">订单备注：</div>
                                <div class="line-value">{{ $order->remark ?: '-' }}</div>
                            </div>
                            <div class="line">
                                <div class="line-label">订单编号：</div>
                                <div class="line-value">{{ $order->no }}</div>
                            </div>
                            <!-- 输出物流状态 -->
                            <div class="line">
                                <div class="line-label">物流状态：</div>
                                <div class="line-value">{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</div>
                            </div>
                            <!-- 如果有物流信息则展示 -->
                            @if($order->ship_data)
                                <div class="line">
                                    <div class="line-label">物流信息：</div>
                                    <div class="line-value">{{ $order->ship_data['express_company'] }} {{ $order->ship_data['express_no'] }}</div>
                                </div>
                            @endif

                            {{--订单已支付 且状态不是未退款   --}}
                            @if($order->paid_at && $order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
                                <div class="line">
                                    <div class="line-label">退款状态:</div>
                                    <div class="line-value">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}</div>
                                </div>
                                <div class="line">
                                    <div class="line-label">退款理由:</div>
                                    <div class="line-value">{{ $order->extra['refund_reason'] }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="order-summary text-right">
                            <div class="total-amount">
                                <span>订单总价：</span>
                                <div class="value">￥{{ $order->total_amount }}</div>
                            </div>
                            <div>
                                <span>订单状态：</span>
                                <div class="value">
                                    @if($order->paid_at)
                                        @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                                            已支付
                                        @else
                                            {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
                                        @endif
                                    @elseif($order->closed)
                                        已关闭
                                    @else
                                        未支付
                                    @endif
                                </div>
                            </div>
                            @if(!$order->paid_at && !$order->closed)
                                <div class="payment-buttons">
                                    <a href="{{ route('payment.alipay',['order'=>$order->id]) }}"
                                       class="btn btn-success btn-xs">支付宝支付</a>
                                    <a href="{{ route('payment.wechat',['order'=>$order->id]) }}"
                                       class="btn btn-success btn-xs">微信支付</a>
                                </div>
                            @endif
                            @if($order->ship_status === \App\Models\Order::SHIP_STS_DELIVERED)
                                <div class="receive-button">
                                    <form action="{{ route('orders.received' , ['id'=>$order->id]) }}" method="post">
                                        {!! csrf_field() !!}
                                        {{ method_field('PATCH') }}
                                        <button type="button" id="btn-receive" class="btn btn-sm btn-success">订单收货</button>
                                    </form>
                                </div>
                            @endif

                            {{--已支付 去退款--}}
                            @if($order->paid_at && $order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                                <div class="refund-button">
                                    <button class="btn btn-sm btn-danger" id="btn-apply-refund">
                                        申请退款
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            // 确认收货按钮点击事件
            $('#btn-receive').click(function () {
                // 弹出确认框
                swal({
                    title: "确认已经收到商品？",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    buttons: ['取消', '确认收到'],
                })
                    .then(function (ret) {
                        console.log(ret);
                        // 如果点击取消按钮则不做任何操作
                        if (!ret) {
                            return false;
                        }else {
                            // ajax 提交确认操作
                            axios.patch('{{ route('orders.received', ['id'=>$order->id]) }}')
                                .then(function () {
                                    // 刷新页面
                                    location.reload();
                                })
                        }
                    });
            });

        });

        //退款点击事件
        $('#btn-apply-refund').click(function () {
            swal({
                text:'请输入退款理由',
                content:'input',
            }).then(function (input){
                if(!input){
                    swal('退款理由不可以为空','','error');
                    return;
                }

                //退款
                axios.post('{!! route('orders.apply_refund',[$order->id]) !!}' , {reason:input}).
                    then(function(){
                        swal('申请退款成功','','success').then(function () {
                            location.reload();
                        });
                })
            })
        })
    </script>
@endsection
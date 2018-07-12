@extends('layouts.app')
@section('title', $product->title)

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-body product-info">
                    <div class="row">
                        <div class="col-sm-5">
                            <img class="cover" src="{{ $product->image }}" alt="{{ $product->title }}">
                        </div>
                        <div class="col-sm-7">
                            <div class="title">{{ $product->title }}</div>
                            <div class="price"><label>价格</label><em>￥</em><span>{{ $product->price }}</span></div>
                            <div class="sales_and_reviews">
                                <div class="sold_count">累计销量 <span class="count">{{ $product->sold_count }}</span></div>
                                <div class="review_count">累计评价 <span class="count">{{ $product->review_count }}</span></div>
                                <div class="rating" title="评分 {{ $product->rating }}">评分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
                            </div>
                            <div class="skus">
                                <label>选择</label>
                                <div class="btn-group" data-toggle="buttons">
                                    @foreach($product->skus as $sku)
                                        <label
                                                class="btn btn-default sku-btn"
                                                data-price="{{ $sku->price }}"
                                                data-stock="{{ $sku->stock }}"
                                                data-toggle="tooltip"
                                                title="{{ $sku->description }}"
                                                data-placement="bottom">
                                            <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="cart_amount"><label>数量</label><input type="text" class="form-control input-sm" value="1"><span>件</span><span class="stock"></span></div>
                            <div class="buttons">
                                @if($favored)
                                    <button class="btn btn-danger btn-disfavor">取消收藏</button>
                                @else
                                    <button class="btn btn-success btn-favor">❤ 收藏</button>
                                @endif
                                <button class="btn btn-primary btn-add-to-cart">加入购物车</button>
                            </div>
                        </div>
                    </div>
                    <div class="product-detail">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab">商品详情</a></li>
                            <li role="presentation"><a href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab">用户评价</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="product-detail-tab">
                                {!! $product->description !!}
                            </div>
                            <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
                        <!-- 评论列表开始 -->
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <td>用户</td>
                                <td>商品</td>
                                <td>评分</td>
                                <td>评价</td>
                                <td>时间</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->order->user->name }}</td>
                                    <td>{{ $review->productSku->title }}</td>
                                    <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                                    <td>{{ $review->review }}</td>
                                    <td>{{ $review->reviewed_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- 评论列表结束 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
            $('.sku-btn').click(function () {
                $('.product-info .price span').text($(this).data('price'));
                $('.product-info .stock').text('库存：' + $(this).data('stock') + '件');
            });
            $('.btn-favor').click(function () {
                axios.post('{!! route('product.disfavorite',['product'=>$product->id]) !!}')
                    .then(function () {
                        swal('操作成功', '', 'success').then(function () {
                            location.reload();
                        });
                    }, function (error) {
                    console.log(error);
                        if (error.response && error.response.status === 401) {
                            swal('请先登录', '', 'success');
                        } else {
                            swal('系统错误', '', 'error');
                        }
                });
            });
            $('.btn-disfavor').click(function () {
                axios.delete('{!! route('product.disfavorite',['product'=>$product->id]) !!}')
                    .then(function () {
                        swal('操作成功', '', 'success').then(function () {
                            location.reload();
                        });
                    }, function (error) {
                        console.log(error);
                        if (error.response && error.response.status === 401) {
                            swal('请先登录', '', 'success');
                        } else {
                            swal('系统错误', '', 'error');
                        }
                    });
            });
            $('.btn-add-to-cart').click(function () {
                axios.post('{!! route('cart.store') !!}',{
                    sku_id: $('label.active input[name=skus]').val(),
                    amount : $('.cart_amount input').val(),
                })
                    .then(function () {
                        swal('添加成功', '', 'success').then(function () {
                            location.href = '{{ route('cart.index') }}';
                        });
                    }, function (error) {
                        if(error.response){
                            if (error.response.status === 401) {
                                swal('请先登录', '', 'success');
                                return;
                            }
                            if (error.response.status === 422) {
                                let html = '<div>';
                                _.each(error.response.data.errors, function (errors) {
                                    _.each(errors, function (error) {
                                        html += error+'<br>';
                                    })
                                });
                                html += '</div>';
                                swal({content: $(html)[0], icon: 'error'})
                                return;
                            }
                            swal('系统错误', '', 'error');
                            return;
                        }
                    });
            });
        });
    </script>
@endsection
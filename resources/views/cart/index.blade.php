@extends('layouts.app')

@section('title', '购物车')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">我的购物车</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>商品信息</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="product_list">
                        @foreach($cartItems as $item)
                            <tr data-id="{{ $item->productSku->id }}">
                                <td>
                                    <input type="checkbox" name="select"
                                           value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
                                </td>
                                <td class="product_info">
                                    <div class="preview">
                                        <a target="_blank"
                                           href="{{ route('products.show', [$item->productSku->product_id]) }}">
                                            <img src="{{ $item->productSku->product->image }}" height="200px"
                                                 width="200px">
                                        </a>
                                    </div>
                                    <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
              <span class="product_title">
                <a target="_blank"
                   href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
              </span>
                                        <span class="sku_title">{{ $item->productSku->title }}</span>
                                        @if(!$item->productSku->product->on_sale)
                                            <span class="warning">该商品已下架</span>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="price">￥{{ $item->productSku->price }}</span></td>
                                <td>
                                    <input type="text" class="form-control input-sm amount"
                                           @if(!$item->productSku->product->on_sale) disabled @endif name="amount"
                                           value="{{ $item->amount }}">
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-danger btn-remove">移除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            $('.btn-remove').click(function () {
                var id = $(this).closest('tr').data('id');
                axios.delete('/cart/'+id)
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

            $('#select-all').change(function() {
                // 获取单选框的选中状态
                // prop() 方法可以知道标签中是否包含某个属性，当单选框被勾选时，对应的标签就会新增一个 checked 的属性
                var checked = $(this).prop('checked');
                // 获取所有 name=select 并且不带有 disabled 属性的勾选框
                // 对于已经下架的商品我们不希望对应的勾选框会被选中，因此我们需要加上 :not([disabled]) 这个条件
                $('input[name=select][type=checkbox]:not([disabled])').each(function() {
                    // 将其勾选状态设为与目标单选框一致
                    $(this).prop('checked', checked);
                });
            });
        });
    </script>
@endsection
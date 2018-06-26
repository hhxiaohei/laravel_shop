@extends('layouts.app')
@section('title','商品列表')

@section('content')
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="panel panel-success">
                <div class="panel-body">
                    {{--筛选组件start--}}
                    <div class="row">
                        <form action="{{ route('products.index') }}" class="form-inline search-form">
                            <input type="text" class="form-control input-sm" name="search" placeholder="搜索">
                            <button class="btn btn-success btn-sm">搜索</button>
                            <select name="order" class="form-controller input-sm pull-right">
                                <option value="">排序方式</option>
                                <option value="price_desc">价格从高到低</option>
                                <option value="price_asc">价格从低到高</option>
                                <option value="sold_count_desc">销量从高到低</option>
                                <option value="sold_count_asc">销量从低到高</option>
                                <option value="rating_desc">评价从高到低</option>
                                <option value="rating_asc">评价从低到高</option>
                            </select>
                        </form>
                    </div>
                    {{--筛选组件end--}}
                    <div class="row products-list">
                        @foreach($products as $product)
                            <a href="{{ route('products.show' ,['id'=>$product->id]) }}">
                                <div class="col-xs-4 product-item">
                                    <div class="product-content">
                                        <div class="top">
                                            <div class="img"><img src="{{ $product->image }}" alt="{{ $product->title }}" class="thumbnail" width="100%" height="300px"></div>
                                            <div class="price"><b>$</b>{{ $product->price }}</div>
                                            <div class="title">{{ $product->title }}</div>
                                        </div>
                                        <div class="bottom">
                                            <div class="sold_count">销量 <span>
                                            {{ $product->sold_count }}
                                        </span></div>
                                            <div class="review_count">评价 <span>
                                            {{$product->review_count}}
                                        </span></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="text-center">{{ $products->render() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script>
        const filters = {!! json_encode($filters) !!};
        $(document).ready(function(){
            $('.search-form input[name=search]').val(filters.search);
            $('.search-form select[name=order]').val(filters.order);
        })
        //失去焦点即筛选(提交表单)
        $('.search-form input[name=search]').on('blur',function () {
            $('.search-form').submit();
        });
        //选择即筛选(提交表单)
        $('.search-form select[name=order]').on('change',function () {
            $('.search-form').submit();
        });
    </script>
@endsection
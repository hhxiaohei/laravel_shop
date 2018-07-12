<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $builder = Product::query()->where('on_sale', true);
        $builder->when($search = $request->search, function ($q) use (&$search) {
            return $q->where('title', 'like', "%{$search}%");
        })->when($order = $request->order, function ($q) use ($order) {
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
//                dump($m[1]);
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $q->orderBy($m[1], $m[2]);
                }
            }
            return $q;
        });
        $products = $builder->paginate()->appends($request->toArray());
        return view('products.index', [
            'products' => $products,
            'filters'  => [
                'order'  => $order,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Request $request)
    {
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }
        $favored = false;
        if ($user = $request->user()) {
            $favored = (bool)$user->favoriteProducts()->find($product->id);
        }
        $reviews = OrderItem::query()
            ->with(['order.user','productSku'])
            ->where('product_id',$product->id)
            ->whereNotNull('reviewed_at')
            ->orderByDesc('reviewed_at')
            ->limit(10)
            ->get();
        return view('products.show', ['product' => $product, 'favored' => $favored,'reviews'=>$reviews]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //新增收藏
    public function favorite(Product $product , Request $request)
    {
        $user = $request->user();
        if(!$user->favoriteProducts()->find($product->id)){
            $user->favoriteProducts()->attach($product);
        }
        return [];
    }

    //取消收藏
    public function disfavorite(Product $product,Request $request)
    {
        $request->user()->favoriteProducts()->detach($product);
        return [];
    }

    //收藏列表信息
    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate();
        return view( 'products.favorites', ['products'=>$products]);
    }
}

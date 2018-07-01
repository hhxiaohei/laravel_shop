<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //地址
        $addresses = \Auth::user()->addresses()->get();
        //显示购物车产品
        $cartItems = $request->user()->cartItems()->with('productSku.product')->get();
        return view('cart.index',compact('cartItems' , 'addresses'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCartRequest $request)
    {
        $amount = $sku_id = null;
        extract($request->only(['sku_id','amount']));
        $user = $request->user();

        //去购物车中判断sku_id是否存在 存在则加一  不存在则创建
        if($cart = $user->cartItems()->where('product_sku_id',$sku_id)->first()){
            $cart->amount += $amount;
        }else{
            $cart = new CartItem(['amount'=>$amount]);
            //创建关联外键信息 一起保存
            $cart->user()->associate($user);
            $cart->productSku()->associate($sku_id);
        }
        $cart->save();
        return [];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id , Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id',$id)->delete();
        return [];
    }
}

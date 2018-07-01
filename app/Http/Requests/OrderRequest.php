<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_id'     => ['required',
                Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id)],
            'items'          => ['required', 'array'],
            'note'           => ['string', 'nullable'],
            'items.*.sku_id' => [//检查items下每个sku_id(存在 售完 下架)
                'required',
                function ($attributes, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        $fail('商品不存在!');
                    }
                    if (!$sku->product->on_sale) {
                        $fail('商品未上架');
                    }
                    if (!$sku->stock) {
                        $fail('商品已售完');
                    }

                    //通过js 数组索引 来取键
                    //$attributes = 'items.0.sku.id';
                    //思路一 正则
                    preg_match('/items\.(\d+).sku_id/', $attributes, $m);
                    $index = $m[1];
                    //思路二 点分割为数组 取值
//                    $tmp_arr = explode('.' , $attributes);
//                    $index = $tmp_arr[1] ?? null;
//                    dd($index);

                    //获取购买数量
                    $amount = $this->input('items')[$index]['amount'];
                    if (is_int($amount) && $amount > $sku->stock) {
                        $fail('商品库存不足');
                    }
                },
            ],
            //商品数量检测
            'items.*.amount' => ['required', 'integer', 'min:1',],
        ];
    }
}

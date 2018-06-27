<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Foundation\Http\FormRequest;

class AddCartRequest extends FormRequest
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
            'sku_id' => [
                'required',
                function ($attribute , $value ,$fail) {
                    if(!$sku = ProductSku::find($value)){
                        $fail('商品不存在');
                    }
                    if(!$sku->product->on_sale){
                        $fail('商品未上架');
                    }
                    if(!$sku->stock){
                        $fail('商品无库存');
                    }
                    if($this->input('amount') > $sku->stock){
                        $fail('商品库存不足!');
                    }
                },
            ],
            'amount' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'sku_id' => 'sku 号',
            'amount' => '商品数量',
        ];
    }
}

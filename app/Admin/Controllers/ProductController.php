<?php

namespace App\Admin\Controllers;

use App\Models\Product;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProductController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('商品列表');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Product::class, function (Grid $grid) {

            $grid->id('ID')->sortable('id');
            $grid->title('商品名称');
            $grid->image('商品封面图片文件路径')->image('',40,40);
            $grid->on_sale('商品是否正在售卖')->display(function ($data) {
                return $data ? "是" : "否";
            });
            $grid->rating('商品平均评分');
            $grid->sold_count('销量');
            $grid->review_count('评价数量');
            $grid->price('SKU 最低价格');
            $grid->created_at('创建时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Product::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title', '商品名称')->rules('required');
            $form->editor('description', '商品详情')->rules('required');
            $form->file('image', '商品封面图片文件路径')->rules('required');
            $form->radio('on_sale', '商品是否正在售卖')->options([0 => '否', 1 => '是']);
//            $form->display('rating','商品平均评分');
//            $form->display('sold_count','销量');
//            $form->display('review_count','评价数量');
//            $form->display('price','SKU 最低价格');
            $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
                $form->text('title', 'SKU 名称')->rules('required');
                $form->text('description', 'SKU 描述')->rules('required');
                $form->text('price', '单价')->rules('required|numeric|min:0.01');
                $form->text('stock', '剩余库存')->rules('required|integer|min:0');
            });
            $form->display('created_at', 'Created At');
            $form->saving(function (Form $form) {
                $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price');
            });
        });
    }
}

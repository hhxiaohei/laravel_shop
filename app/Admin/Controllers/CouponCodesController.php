<?php

namespace App\Admin\Controllers;

use App\Models\CouponCode;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CouponCodesController extends Controller
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

            $content->header('优惠券列表');
            $content->description('description');

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
        return Admin::grid(CouponCode::class, function (Grid $grid) {

            $grid->model()->orderBy('created_at', 'desc');
            $grid->id('ID')->sortable();
            $grid->columns([
                'name'       => '名称',
                'description'       => '规则',
                'code'       => '优惠码',
                'min_amount' => '最低金额',
                'total'      => '总量',
                'used'       => '已用',
                'created_at' => '创建时间',
            ]);
            $grid->type('类型')->display(function ($value) {
                return CouponCode::$TYPE_MAP[$value];
            });
            // 根据不同的折扣类型用对应的方式来展示
            $grid->value('折扣')->display(function ($value) {
                return $this->type === CouponCode::TYPE_FIXED ? '￥' . $value : $value . '%';
            });
            $grid->enabled('是否启用')->display(function ($value) {
                return $value ? '是' : '否';
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(CouponCode::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}

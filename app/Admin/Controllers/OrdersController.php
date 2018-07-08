<?php

namespace App\Admin\Controllers;

use App\Models\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class OrdersController extends Controller
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

            $content->header('订单列表');

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
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->model()->whereNotNull('paid_at')->orderBy('paid_at');
            $grid->columns([
                'total_amount' => '订单总金额',
                'paid_at'      => '支付时间',
                'user.name'    => '买家',
                'no'           => '订单流水号',
            ]);
            $grid->ship_status('物流状态')->display(function ($item) {
                return Order::$shipStatusMap[$item];
            });
            $grid->refund_status('退款状态')->display(function ($item) {
                return Order::$refundStatusMap[$item];
            });

            $grid->disableCreateButton();
            //删除 编辑
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();

                $actions->append("<a href='". route('admin.orders.show',['id'=>$actions->getKey()]) ."'. class='btn btn-primary btn-xs'>查看</a>");
            });
            //批量删除
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
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
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    //自定义显示详情
    public function show(Order $order)
    {
        return Admin::content(function (Content $content) use (&$order){
            $content->header('查看订单');
            $content->body(view('admin.orders.show',compact('order')));
        });
    }
}

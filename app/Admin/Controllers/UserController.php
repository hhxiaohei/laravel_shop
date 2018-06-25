<?php

namespace App\Admin\Controllers;

use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
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

            $content->header('用户列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
//    public function edit($id)
//    {
//        return Admin::content(function (Content $content) use ($id) {
//
//            $content->header('header');
//            $content->description('description');
//
//            $content->body($this->form()->edit($id));
//        });
//    }

    /**
     * Create interface.
     *
     * @return Content
     */
//    public function create()
//    {
//        return Admin::content(function (Content $content) {
//
//            $content->header('header');
//            $content->description('description');
//
//            $content->body($this->form());
//        });
//    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('用户名');
            $grid->email('用户邮箱');
            $grid->email_verified('已验证邮箱')->display(function($value){
                return $value ? '是':'否';
            });
            $grid->created_at('注册时间');
            $grid->disableCreateButton();
            $grid->actions(function($action){
                $action->disableDelete();
                $action->disableEdit();
            });
            $grid->tools(function($tools){
                $tools->batch(function($batch){
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
//    protected function form()
//    {
//        return Admin::form(User::class, function (Form $form) {
//
//            $form->display('id', 'ID');
//
//            $form->display('created_at', 'Created At');
//            $form->display('updated_at', 'Updated At');
//        });
//    }
}

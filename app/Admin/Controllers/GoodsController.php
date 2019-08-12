<?php

namespace App\Admin\Controllers;

use App\Model\AdminGoodModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Good\insert;
class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminGoodModel);

        $grid->column('goods_id', __('Goods id'));
        $grid->column('goods_sn', __('Goods sn'));
        $grid->column('goods_name', __('Goods name'));
//        $grid->column('goods_img', __('Goods img'));
        $grid->goods_img('goods_img')->display(function($goods_img){
            return '<img src=/storage/'.$goods_img.'>';
        });
        $grid->column('short_desc', __('Short desc'));
        $grid->column('price0', __('Price0'));
        $grid->column('price', __('Price'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('is_delete', __('Is delete'));
        $grid->column('is_onsale', __('Is onsale'));


        $grid->actions(function ($actions) {
            $actions->add(new insert);
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AdminGoodModel::findOrFail($id));

        $show->field('goods_id', __('Goods id'));
        $show->field('goods_sn', __('Goods sn'));
        $show->field('goods_name', __('Goods name'));
        $show->field('goods_img', __('Goods img'));
        $show->field('short_desc', __('Short desc'));
        $show->field('price0', __('Price0'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
//      $show->field('updated_at', __('Updated at'));
        $show->field('is_delete', __('Is delete'));
        $show->field('is_onsale', __('Is onsale'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AdminGoodModel);

        $form->text('goods_sn', __('Goods sn'));
        $form->text('goods_name', __('Goods name'));
//        $form->text('goods_img', __('Goods img'));
        $form->image('goods_img')->move('/upload/goods_img');
        $form->text('short_desc', __('Short desc'));
        $form->number('price0', __('Price0'));
        $form->number('price', __('Price'));
//        $form->datetime('created_at', __('Created at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('updated_at', __('Updated at'))->default(date('Y-m-d H:i:s'));
        $form->switch('is_delete', __('Is delete'));
        $form->switch('is_onsale', __('Is onsale'))->default(1);

        return $form;
    }
}

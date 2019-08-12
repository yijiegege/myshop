<?php

namespace App\Admin\Actions\Good;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class insert extends RowAction
{
    public $name = '添加';

//    public function handle(Model $model)
//    {
//        // $model ...
//
//        return $this->response()->success('Success message.')->refresh();
//    }
    public function href()
    {
        return "/admin/goods/create";
    }
}
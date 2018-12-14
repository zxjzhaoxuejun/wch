<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-07-14
 * Time: 14:25
 */
namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Registration as RegModel;


class Registration extends Base{
//列表展示
    public function index()
    {
        $data=RegModel::paginate(5);
        $page=$data->render();
        $this->assign('page',$page);
        $this->assign('data',$data);
        return $this->fetch('registration/list');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-08-07
 * Time: 18:19
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Enroll as EnrollModel;
use think\Request;

class Enroll extends Base{
    /**
     * 评选报名列表
     * @return mixed
     */
    public function index(){
        $data=EnrollModel::all();
        $this->assign('data',$data);
        return $this->fetch('list');
    }


}
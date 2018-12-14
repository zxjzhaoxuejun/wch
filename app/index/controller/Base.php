<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/10/19
 * Time: 14:21
 */
namespace app\index\controller;
use think\Controller;
use think\Session;

class Base extends Controller{
    public function _initialize()
    {
        parent::_initialize();
//        创建一个常量判断是否登录
        define('USER_LOGIN',Session::get('userData'));
        $this->islogin();
    }

    /**
     * 判断是否登录
     */
    public function islogin()
    {
        if(is_null(USER_LOGIN)){
            $this->error('未登录，无权访问!','index/login');
        }
    }

}
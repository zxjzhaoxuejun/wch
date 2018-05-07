<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/18 0018
 * Time: 下午 20:51
 */
namespace app\admin\common;

use app\admin\model\Province;
use think\Cache;
use think\Controller;
use think\Session;

class Base extends Controller{

    public function _initialize()
    {
        parent::_initialize();
//        创建一个常量判断是否登录
        define('USER_ID',Session::get('user_id'));
        $this->isLogin();

//        $city=Province::all();
//        $json=json_encode($city,JSON_UNESCAPED_UNICODE);
//        $c='var city='.$json;
//        file_put_contents('./static/admin/js/city.js',$c);

    }


//    未登录
    public function isLogin()
    {
        if(is_null(USER_ID)){
            $this->error('未登录，无权访问！','Login/index');
        }
    }
//    已登录重复登录
//    public function alreadLogin()
//    {
//        if(!is_null(USER_ID)){
//            $this->error('已登录，请不要重复登录！','Index/index');
//        }
//    }


    
}

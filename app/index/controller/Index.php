<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/12
 * Time: 9:50
 */
namespace app\index\controller;
use app\admin\controller\Output;
use app\admin\model\MemberService;
use think\Controller;

class Index extends Controller {
    public function index()
    {
        $d=MemberService::get(1);
        $this->assign('data',$d);
        return $this->fetch('index');
    }
}
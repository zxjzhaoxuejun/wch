<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/12
 * Time: 9:33
 */
namespace app\admin\controller;
use app\admin\model\MemberService;
use app\admin\model\MemberSign;

class Output{

    /**
     * 个人会员数据
     * @return \think\response\Json
     */
    public function memberSignData(){
        $data=MemberSign::all();
        $wx_info = json($data,true);
        return $wx_info;
    }

    /**
     * @return \think\response\Json
     * 会员服务
     */
    public function memberServiceData()
    {
        $data=MemberService::get(1);
        return json($data);
    }
}
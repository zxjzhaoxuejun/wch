<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/11
 * Time: 9:32
 */

namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\MemberService as ServiceModel;
use think\Request;

class Services extends Base{

    /**
     * 显示会员服务列表
     *
     * @return \think\Response
     */
    public function index(){
        $data=ServiceModel::get(1);
        $this->assign('data',$data);
        return $this->fetch('member_service');
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        if($request->isAjax(true)){
            $data=$request->param();
            $sys=new ServiceModel();
            $res=$sys->allowField(true)->save($data,['id'=>1]);
            if($res){
                $state=[
                    'msg'=>'保存成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'保存失败!',
                    'code'=>0
                ];
            }
            return $state;
        }
    }

}
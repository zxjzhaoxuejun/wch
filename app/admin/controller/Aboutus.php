<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/11
 * Time: 10:43
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\AboutUs as AboutUsModel;
use think\Request;

class Aboutus extends Base{

    /**
     * 协会简介
     * @return mixed
     */
    public function index(){
        $data=AboutUsModel::get(1);
        $this->assign('info',$data);

        return $this->fetch('abstract');
    }


    /**
     * 组织架构
     * @return mixed
     */
    public function framework(){
        $data=AboutUsModel::get(1);
        $this->assign('info',$data);

        return $this->fetch('framework');
    }

    /**
     * 分支机构
     * @return mixed
     */
    public function organization(){
        $data=AboutUsModel::get(1);
        $this->assign('info',$data);

        return $this->fetch('organization');
    }

    /**
     * 入会指南
     * @return mixed
     */
    public function initiation(){
        $data=AboutUsModel::get(1);
        $this->assign('info',$data);

        return $this->fetch('initiation');
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
            $sys=new AboutUsModel();
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
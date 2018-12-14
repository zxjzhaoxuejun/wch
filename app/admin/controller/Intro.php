<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/11
 * Time: 14:51
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Intro as IntroModel;
use think\Request;

class Intro extends Base{
//    简介
    public function index()
    {
        $data=IntroModel::get(1);
        $this->assign('data',$data);
        return $this->fetch('show');
    }
//活动背景
    public function bj()
    {
        $data=IntroModel::get(2);
        $this->assign('data',$data);
        return $this->fetch('bj');
    }
//奖项介绍
    public function sz()
    {
        $data=IntroModel::get(3);
        $this->assign('data',$data);
        return $this->fetch('sz');
    }
//区域设置
    public function bz()
    {
        $data=IntroModel::get(4);
        $this->assign('data',$data);
        return $this->fetch('bz');
    }
//评选标准
    public function ap()
    {
        $data=IntroModel::get(5);
        $this->assign('data',$data);
        return $this->fetch('ap');
    }

    //奖项设置
    public function jx()
    {
        $data=IntroModel::get(6);
        $this->assign('data',$data);
        return $this->fetch('jx');
    }

    //时间安排
    public function sj()
    {
        $data=IntroModel::get(7);
        $this->assign('data',$data);
        return $this->fetch('sj');
    }

    public function save(Request $request)
    {
        $data=$request->param();
        $m=new IntroModel();
        $i=$m->where('id',$data['id'])->find();
        if($i){
            $res=$m->allowField(true)->save($data,['id'=>$data['id']]);
        }else{
            $res=$m->allowField(true)->save($data);
        }

        if($res){
            $state=[
                'msg'=>'修改成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'修改失败!',
                'code'=>0
            ];
        }
        return $state;
    }
}
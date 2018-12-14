<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/11/19
 * Time: 9:23
 */
namespace app\index\controller;
use app\admin\model\Banner;
use think\Controller;
use app\admin\model\Nav;
use app\admin\model\MemberReg;
use app\admin\model\System;
use app\admin\model\Apply as ApplyModel;
use think\Request;
use think\Session;

class Apply extends Controller{

    public function index()
    {
        $navList=(new Nav())->navOut();//导航
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',7)->limit(1)->order('create_time','desc');
        });
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','2018年深圳技能大赛——网页制作职业技能竞赛报名');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        return $this->fetch('apply/index');
    }

    public function save(Request $request)
    {
        if($request->isAjax(true)){//identity
            $data=$request->param();
            $a=new ApplyModel();
            $h=$a->where('identity',$data['identity'])->find();
            if($h){
                $this->error('此用户已报名，请不要重复报名！');
            }
            $res=$a->allowField(true)->save($data);
            if($res){
                $this->success('提交报名表成功！');
            }else{
                $this->error('报名表提交失败！');
            }
        }
    }

}
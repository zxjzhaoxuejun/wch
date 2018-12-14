<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/10/19
 * Time: 11:07
 */
namespace app\index\controller;
use app\admin\model\Nav;
use app\admin\model\System;
use app\admin\model\Banner;//轮播图
use app\admin\model\MemberReg;//注册
use app\admin\controller\Mail;//发布邮件
use think\Request;
use app\index\controller\Base;
use app\admin\model\Writing;
use think\Session;

class User extends Base{

    /**
     * 用户信息
     */
    public function user_info(Request $request)
    {

        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',4)->limit(1)->order('create_time','desc');
        });
        $leftNav=['基本信息','我的发布','密码修改','全部文章'];//2,3,4

        $this->assign('pageTitle','个人中心');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('leftNav',$leftNav);
        $r=new MemberReg();
        $users=$r->where('email',Session::get('userData'))->find();
        $this->assign('info',$users);
        $data=$request->param('id');
        if(!isset($data)||$data==0){//基本信息
            $this->assign('navId',0);
            $this->assign('navTitle','基本信息');
            return $this->fetch('user_base');
        }
        else if($data==1){//我的发布
            $w=new Writing();
            $list=$w->where('uid',$users['rid'])->order('create_time','desc')->paginate(8);
            $count=$w->where('uid',$users['rid'])->count();
            $this->assign('list',$list);
            $this->assign('count',$count);
            $this->assign('navId',1);
            $this->assign('handle',true);
            $this->assign('navTitle','我的发布');
            return $this->fetch('user_list');
        }
        else if($data==2){//修改密码
            $this->assign('navId',2);
            $this->assign('navTitle','修改密码');
            return $this->fetch('user_password');
        }
        else if($data==3){//全部文章
            $this->assign('navId',3);
            $w=new Writing();
            $list=$w->order('create_time','desc')->paginate(8);
            $count=$w->count();
            $this->assign('list',$list);
            $this->assign('count',$count);
            $this->assign('handle',false);
            $this->assign('navTitle','全部文章');
            return $this->fetch('user_list');
        }

    }


    /**
     * 邮件发送
     */
    public function to_email(Request $request)
    {

    }

    /**
     * 邮箱认证
     */
    public function email_approve(Request $request)
    {
        $data=$request->param();
        $reg=new MemberReg();
        $res=$reg->where('email',$data['email'])->find();
        if($res['email_code']==$data['email_code']){
            $res->save(['status'=>1]);
            Session::set('userData',$res['email']);//保存登录的用户信息
            $this->success('认证成功！','','code');
        }else{
            $this->error('验证码输入错误！','','email_code');
        }
    }

    /**
     * 密码修改
     */
    public function edit_pass(Request $request)
    {
        $data=$request->param();
        $reg=new MemberReg();
        $res=$reg->where('email',$data['email'])->find();
        if($res['password']==$data['password']){
            $this->success('跟原来密码一样！');
        }else{
            $patten='/^.{6,100}$/';
            if(preg_match($patten,$data['password'])){
                $res->save(['password'=>$data['password']]);
                Session::set('userData',$res['email']);//保存登录的用户信息
                $this->success('密码修改成功！');
            }else{
                $this->error('密码输入6-30字符！');
            }

        }
    }

    /**
     * 修改头像
     */
    public function modify_img(Request $request)
    {
        $data=$request->param();
        $reg=new MemberReg();
        $res=$reg->where('email',$data['email'])->find();
        if($res){
            $res->save(['avator'=>$data['avator']]);
            Session::set('userData',$res['email']);//保存登录的用户信息
            $this->success('头像修改成功!');
        }else{
            $this->error('用户不存在!');
        }
    }

    /**
     * 写文章
     */
    public function write_art()
    {
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        if($t['status']=='未认证'||$t['status']=='审核中'){
            $this->error('未认证，不能写文章！','user_info');
            return;
        }
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',4)->limit(1)->order('create_time','desc');
        });
        $this->assign('info',$t);
        $this->assign('pageTitle','写文章');
        $this->assign('pageId',7);
        $this->assign('copyRight',$copy);
        $this->assign('navList',$navList);
        $this->assign('banner',$banner);
        return $this->fetch('user_write');
    }

    /**
     * 保存发布文章
     */
    public function save(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $w=new Writing();
            $res=$w->allowField(true)->save($data);
            if($res){
                $this->success('文章发布成功!');
            }else{
                $this->error('文章发布失败!');
            }
        }
    }

    /**
     * @param Request $request
     * 编辑文章
     */
    public function wr_edit(Request $request)
    {
        $id=$request->param('id');
        $w=new Writing();
        $data=$w->where('id',$id)->find();
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        if($t['status']=='未认证'||$t['status']=='审核中'){
            $this->error('未认证，不能写文章！','user_info');
            return;
        }
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',4)->limit(1)->order('create_time','desc');
        });
        $this->assign('info',$t);
        $this->assign('pageTitle','写文章');
        $this->assign('pageId',7);
        $this->assign('copyRight',$copy);
        $this->assign('navList',$navList);
        $this->assign('banner',$banner);
        $this->assign('data',$data);
        return $this->fetch('user_edit');
    }

    /**
     * 编辑保存
     */
    public function update(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $w=new Writing();
            $res=$w->allowField(true)->save($data,['id'=>$data['id']]);
            if($res){
                $this->success('修改成功!');
            }else{
                $this->error('修改失败!');
            }
        }
    }

    /**
     * 删除文章
     */

    public function delete(Request $request)
    {
        if($request->isAjax(true)){
            $id=$request->param('id');
            $res=Writing::destroy($id,true);
            if($res){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败!');
            }
        }
    }
}
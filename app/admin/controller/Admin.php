<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\TbAdmin;
use app\admin\validate\Password;
use think\Request;
class Admin extends Base
{
    /**
     * 显示管理员列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

//        $d=base64_encode('a123456');
//        $v=base64_decode($d);
        $count=TbAdmin::count();
        $data=TbAdmin::paginate(5);
        $page=$data->render();
        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch('admin_list');
    }

    /**
     * 显示创建管理员表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch('admin_add');
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
        if ($request->isAjax(true)){
            $data=$request->param();
            $data['last_time']=time();
            $admin=new TbAdmin();
           $res=$admin->allowField(true)->save($data);
           if($res){
               $state=[
                   'msg'=>'新增管理员成功!',
                  'code'=>1
               ];
           }else{
               $state=[
                   'msg'=>'新增管理员失败!',
                   'code'=>0
               ];
           }
        }
        return $state;
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑管理员表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        //
        $data=TbAdmin::get($request->param('id'));
        $this->assign('data',$data);
        return $this->fetch('admin_edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        //
        if ($request->isAjax(true)){
            $data=$request->param();
            $admin=new TbAdmin();
            $res=$admin->allowField(true)->save($data,['id'=>$data['id']]);
            if($res){
                $state=[
                    'msg'=>'修改管理员成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'修改管理员失败!',
                    'code'=>0
                ];
            }
        }
        return $state;
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        //
        $id=$request->param('id');
        $res=TbAdmin::destroy($id,true);
        if($res){
            $this->success('删除成功！');
        }else{
            $this->success('删除失败！');
        }
    }
    
//    修改密码提交
    public function passwordupdate(Request $request)
    {
        if($request->isAjax(true)){
            $data=array_filter($request->param());

            $val=new Password();
            if(!$val->check($data)){
                $this->error($val->getError());
                exit;
            }
            if($data['password']!=md5($data['oldpassword'])){
                $this->error('旧密码输入错误!');
                exit;
            }

            $admin=new TbAdmin();

            $re=$admin->allowField(true)->save(['password'=>$data['newpassword']],['id'=>$data['id']]);

            if ($re){
                $this->success('修改密码成功！');
            }else{
                $this->success('修改密码失败！','Index/changepass');
            }
        }else{
            $this->success('提交数据操作错误！','Index/changepass');
        }
    }
}

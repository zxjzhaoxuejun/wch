<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\Province;
use think\Request;

use app\admin\model\Member as MemberModel;
class Member extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=MemberModel::withTrashed()->select(function($query){
            $query->where('m_statue','<',2);
        });
        $this->assign('data',$data);
        return $this->fetch('member_list');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $city=Province::all(function ($query){
            $query->where('level',2);
        });
        $this->assign('city',$city);
        return $this->fetch('member_add');
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
            $m=new MemberModel();
            $res=$m->allowField(true)->save($data);
            if($res){
                $state=[
                    'msg'=>'提交成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'提交失败!',
                    'code'=>0
                ];
            }
            return $state;

        }else{
            $this->error('请求数据错误');
        }
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
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        //
        $data=MemberModel::get($request->param('id'));
        $this->assign('data',$data);
        $city=Province::all(function ($query){
            $query->where('level',2);
        });
        $this->assign('city',$city);
        return $this->fetch('member_edit');
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
        if($request->isAjax(true)){
            $data=$request->param();
            $nav=new MemberModel();
            $res=$nav->allowField(true)->save($data,['m_id'=>$data['m_id']]);

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
        //
    }

    /**
     * 软删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        //
        $id=$request->param();
        $res=MemberModel::destroy($id['m_id']);
        MemberModel::update(['m_statue'=>2],['m_id'=>$id['m_id']]);

        if($res){
            $state=[
                'msg'=>'删除成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'删除失败!',
                'code'=>0
            ];
        }
        return $state;

    }

    /*
     * 修改状态
     */
    public function statue(Request $request)
    {
        if ($request->isAjax(true)){
            $data=input('post.');

            $m=new MemberModel();
            $res=$m->allowField(true)->save($data,['m_id'=>$data['m_id']]);
            if($res){
                $state=[
                    'msg'=>'提交成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'提交失败!',
                    'code'=>0
                ];
            }
            return $state;

        }else{
            $this->error('请求数据错误');
        }
    }

//    修改密码展示
    public function password(Request $request)
    {
        $id=$request->param('id');
        $this->assign('id',$id);
        return $this->fetch('member_password');
    }
//    保存修改密码
    public function save_pass(Request $request)
    {
        if ($request->isAjax(true)){
            $data=$request->param();
            $m=new MemberModel();
            $res=$m->allowField(true)->save($data,['m_id'=>$data['m_id']]);
            if($res){
                $state=[
                    'msg'=>'提交成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'提交失败!',
                    'code'=>0
                ];
            }
            return $state;
        }else{
            $this->error('请求数据错误');
        }
    }


//    已删除的数据展示列表
    public function del_show()
    {
        $data=MemberModel::withTrashed()->select(function($query){
            $query->where('m_statue',2);
        });
        $this->assign('data',$data);
        return $this->fetch('member_del');
    }
//    真删除
    public function del_check(Request $request)
    {
        $id=$request->param();
        $res=MemberModel::destroy($id['m_id'],true);

        if($res){
            $state=[
                'msg'=>'删除成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'删除失败!',
                'code'=>0
            ];
        }
        return $state;
    }

//    展示会员详细信息
    public function show_info(Request $request)
    {
        $info=MemberModel::withTrashed()->find($request->param('id'));
        $this->assign('info',$info);
        return $this->fetch('member_show');
    }

//    会员积分
    public function integral()
    {
        $data=MemberModel::withTrashed()->select(function($query){
            $query->where('m_statue','<',2);
        });
        $this->assign('data',$data);
        return $this->fetch('member_integral');
    }
//    会员等级
    public function level()
    {
        $data=MemberModel::withTrashed()->select(function($query){
            $query->where('m_statue','<',2);
        });

        $this->assign('data',$data);
        return $this->fetch('member_level');
    }

}

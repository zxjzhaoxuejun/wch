<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\Feedback;
use app\admin\model\Messages as MessagesModel;
use think\Request;

class Messages extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=Feedback::all();
        $this->assign('data',$data);
        return $this->fetch('feedback_list');
    }

    public function p_index()
    {
        $data=MessagesModel::all();
        $this->assign('data',$data);
        return $this->fetch('messages_list');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $data=$request->param();
        $res=MessagesModel::destroy($data['p_id'],true);
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

    /**
     * @param Request $request
     * @return array
     * 反馈内容,评论的状态
     */
    public function f_statue(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $f=new Feedback();
            $res=$f->allowField(true)->save($data,['f_id'=>$data['f_id']]);

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



    public function p_statue(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $f=new MessagesModel();
            $res=$f->allowField(true)->save($data,['p_id'=>$data['p_id']]);

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

    /**
     * @param Request $request
     * @return array
     */
    public function f_del(Request $request)
    {
            $data=$request->param();
            $res=Feedback::destroy($data['f_id'],true);
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



}

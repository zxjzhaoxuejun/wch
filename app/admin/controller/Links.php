<?php

namespace app\admin\controller;


use app\admin\common\Base;
use think\Request;
use app\admin\model\Links as LinksModel;

class Links extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data=LinksModel::all(function ($query){
            $query->order('order','asc');
        });
        $count=LinksModel::count();
        $this->assign('count',$count);
        $this->assign('data',$data);
        return $this->fetch('list');

        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if ($request->isAjax(true)){
            $data=$request->param();
            $nav=new LinksModel();
            $res=$nav->allowField(true)->save($data);

            if($res){
                $state=[
                    'msg'=>'新增链接成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'新增链接失败!',
                    'code'=>0
                ];
            }

            return $state;
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
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        $info=LinksModel::get($request->param('id'));
        $this->assign('info',$info);
        return $this->fetch('edit');
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
        if($request->isAjax(true)){
            $data=$request->param();
            $nav=new LinksModel();
            $res=$nav->allowField(true)->save($data,['id'=>$data['id']]);

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
     * 删除指定资源
     *
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $id=$request->param();
        $res=LinksModel::destroy($id['id'],true);
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
     * 修改排序
     */
    public function changeOrder(Request $request)
    {
        if($request->isPost(true)){
            $data=$request->param();
            $nav=new LinksModel();
            $res=$nav->allowField(true)->save($data,['id'=>$data['id']]);
            if($res){
                $state=[
                    'msg'=>'修改排序成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'修改排序失败!',
                    'code'=>0
                ];
            }

            return $state;
        }

    }

}

<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use think\Request;
use app\admin\model\Category as CategoryModel;
class Category extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
//        数据记录总条数
        $count=CategoryModel::count();
        $this->assign('count',$count);
        $data=(new CategoryModel())->tree();//分层展示数据
        $this->assign('data',$data);
        return $this->fetch('category_list');
    }

    /**
     * 显示创建分类表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $data=CategoryModel::all(function ($query){
            $query->order('cate_order','asc')->where('cate_pid',0);
        });
        $this->assign('data',$data);
        return $this->fetch('category_add');
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
            $cate=new CategoryModel();
            $res=$cate->allowField(true)->save($data);
            if($res){
                $state=[
                    'msg'=>'新增分类成功!',
                    'code'=>1
                ];
                $user_1=session('data');
                $log=[
                    'type'=>'分类管理模块',
                    'content'=>'成功添加一个分类!',
                    'editor'=>$user_1['username'],
                ];
                $logmodel=new SystemLog();
                $logmodel->allowField(true)->save($log);

            }else{
                $state=[
                    'msg'=>'新增分类失败!',
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
     * 显示编辑表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        //
        $data=(new CategoryModel())->tree();
        $this->assign('data',$data);
        $info=CategoryModel::get($request->param('id'));
        $this->assign('info',$info);
        return $this->fetch('category_edit');
    }

    /**
     * 保存更新的分类
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
            $cate=new CategoryModel();
            $res=$cate->allowField(true)->save($data,['cate_id'=>$data['cate_id']]);
            if($res){
                $state=[
                    'msg'=>'修改分类成功!',
                    'code'=>1
                ];

            }else{
                $state=[
                    'msg'=>'修改分类失败!',
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
        $id=$request->param();
        $res=CategoryModel::destroy($id['cate_id'],true);
        $data=new CategoryModel();


        if(is_array($id['cate_id'])) {
            foreach ($id['cate_id'] as $d) {
                $data->where('cate_pid', $d)->update(['cate_pid' => 0]);
            }
        }else{
            $data->where('cate_pid',$id['cate_id'])->update(['cate_pid' => 0]);
        }

        if($res){
            $state=[
                'msg'=>'删除分类成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'删除分类失败!',
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
            $cate=new CategoryModel();
            $res=$cate->allowField(true)->save($data,['cate_id'=>$data['cate_id']]);
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

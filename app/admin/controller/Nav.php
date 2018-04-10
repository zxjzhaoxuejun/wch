<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/27
 * Time: 13:21
 */

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Nav as NavModel;
use think\Request;

class Nav extends Base{
    //列表展示
    public function index()
    {
        $count=NavModel::count();
        $this->assign('count',$count);

        $data=(new NavModel())->tree();
        $this->assign('data',$data);
        return $this->fetch('nav/nav_list');
    }

    //添加栏目
    public function create()
    {
        $data=(new NavModel())->tree();
        $this->assign('data',$data);
        return $this->fetch('nav/nav_add');
    }

    //保存添加栏目
    public function save(Request $request)
    {
        if ($request->isAjax(true)){
            $data=$request->param();
            $nav=new NavModel();
            $res=$nav->allowField(true)->save($data);

            if($res){
                $state=[
                    'msg'=>'新增栏目成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'新增栏目失败!',
                    'code'=>0
                ];
            }

            return $state;
        }
    }

//    编辑栏目
    public function edit(Request $request)
    {
        $data=(new NavModel())->tree();
        $this->assign('data',$data);

        $info=NavModel::get($request->param('id'));
        $this->assign('info',$info);
        return $this->fetch('nav/nav_edit');
    }

//    修改提交
    public function update(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $nav=new NavModel();
            $res=$nav->allowField(true)->save($data,['nav_id'=>$data['nav_id']]);

            if($res){
                $state=[
                    'msg'=>'修改栏目成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'修改栏目失败!',
                    'code'=>0
                ];
            }
            return $state;
        }
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
        $res=NavModel::destroy($id['nav_id'],true);
        $data=new NavModel();


        if(is_array($id['nav_id'])) {
            foreach ($id['nav_id'] as $d) {
                $data->where('nav_pid', $d)->update(['nav_pid' => 0]);
            }
        }else{
            $data->where('nav_pid',$id['nav_id'])->update(['nav_pid' => 0]);
        }

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
            $nav=new NavModel();
            $res=$nav->allowField(true)->save($data,['nav_id'=>$data['nav_id']]);
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
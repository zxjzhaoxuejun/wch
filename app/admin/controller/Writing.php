<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/10/25
 * Time: 10:14
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Writing as WriteModel;
use think\Request;

class Writing extends Base{
    /**
     * 显示文章列表
     * @return \think\Response
     */
    public function index()
    {
        //
        $data= WriteModel::all();
        $this->assign('data',$data);
        return $this->fetch('list');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {

        $info=WriteModel::get($request->param('id'));
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
    public function update(Request $request)
    {
        //
        if ($request->isAjax(true)){
            $data=$request->param();
            $data['create_time']=strtotime($data['create_time']);
            if($data['create_time']==''){
                $data['create_time']=time();
            }
            $article=new WriteModel();
            $res=$article->allowField(true)->save($data,['id'=>$data['id']]);
            if($res){
                $state=[
                    'msg'=>'文章修改成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'文章修改失败!',
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
        $id=$request->param();

        $res=WriteModel::destroy($id['id'],true);

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

    //    修改文章状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new WriteModel();
            $res=$art->allowField(true)->save($data,['id'=>$data['id']]);

            if($res){
                $state=[
                    'code'=>1
                ];
            }else{
                $state=[
                    'code'=>0
                ];
            }
            return $state;
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/11
 * Time: 9:21
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Selection as SelectModel;
use think\Request;

class Selection extends Base{
//    显示评选动态列表
    public function index()
    {
        $data=SelectModel::all(function($query){
            $query->where('cate_id=1');
        });

        $this->assign('data',$data);

        return $this->fetch('list');
        
    }

//    创建

    public function create()
    {
        return $this->fetch('add');
    }

//    保存
    public function save(Request $request)
    {
        if ($request->isAjax(true)) {
            $data = $request->param();
            $data['create_time']=strtotime($data['create_time']);
            if($data['create_time']==''){
                $data['create_time']=time();
            }
            $art = new SelectModel();
            $res = $art->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增成功!',
                    'code' => 1
                ];
            } else {
                $state = [
                    'msg' => '新增失败!',
                    'code' => 0
                ];
            }
            return $state;
        }

    }



    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {

        $info=SelectModel::get($request->param('id'));
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
            $article=new SelectModel();
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


    //上传图片
    public function upload(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/selections');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='selections/'.$path;

            return $filepath;
        }
    }

//    修改文章状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new SelectModel();
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

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $id=$request->param();

        $res=SelectModel::destroy($id['id'],true);

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
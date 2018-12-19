<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use think\Request;
use app\admin\model\Figure as FigureModel;
use app\admin\model\Category as CategoryModel;

class Figure extends Base
{
    /**
     * 显示列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data= FigureModel::all();
        $this->assign('data',$data);

        return $this->fetch('list');
    }


    /**
     * 显示创建资讯表单页.
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
        //
        if ($request->isAjax(true)) {
            $data = $request->param();
            $data['create_time']=strtotime($data['create_time']);
            if($data['create_time']==''){
                $data['create_time']=time();
            }
            $art = new FigureModel();
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

        $info=FigureModel::get($request->param('id'));
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
            $article=new FigureModel();
            $res=$article->allowField(true)->save($data,['id'=>$data['id']]);
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

        $res=FigureModel::destroy($id['id'],true);

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


    //上传封面图
    public function upload(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/video');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='video/'.$path;

            return $filepath;
        }
    }
    //上传视频
    public function upload_video(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/video');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='video/'.$path;
            return $filepath;
        }
    }

    

//    修改文章状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new FigureModel();
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

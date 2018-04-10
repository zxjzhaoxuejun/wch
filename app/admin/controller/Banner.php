<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use think\Request;
use app\admin\model\Banner as BannerModel;
class Banner extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=BannerModel::all();
        $this->assign('data',$data);
        return $this->fetch('banner_list');
    }

    /**
     * 显示创建图片表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch('banner_add');
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
            $banner=new BannerModel();
            $res=$banner->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增banner成功!',
                    'code' => 1
                ];
                $user=session('data');
                $log=[
                    'type'=>'轮播图模块',
                    'content'=>'新增banner图!',
                    'editor'=>$user['username'],
                ];
                $logmodel=new SystemLog();
                $logmodel->allowField(true)->save($log);

            } else {
                $state = [
                    'msg' => '新增banner失败!',
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
        //

        $data=BannerModel::get($request->param('id'));
        $this->assign('data',$data);
        return $this->fetch('banner_edit');

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
            $banner=new BannerModel();
            $res= $banner->allowField(true)->save($data,['b_id'=>$data['b_id']]);
            if ($res) {
                $state = [
                    'msg' => '修改banner成功!',
                    'code' => 1
                ];
            } else {
                $state = [
                    'msg' => '修改banner失败!',
                    'code' => 0
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

        $res=BannerModel::destroy($id['id'],true);
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

    //上传图片
    public function upload(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/banner');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='banner/'.$path;

            return $filepath;
        }
    }
}

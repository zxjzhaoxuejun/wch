<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/11
 * Time: 14:19
 */
namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Partisan as PartisanModel;
use think\Request;

class Partisan extends Base{

    /**
     * 理事单位展示方法
     */
    public function  partisan_index(){
        $data=PartisanModel::all();
        $title=[
            "navPosition"=>"关于协会",
            "title"=>"理事单位",
            "imgName"=>"公司logo",
            "linkName"=>"联系人",
            "linkTel"=>"联系方式",
            "name"=>"理事单位名",
            "describe"=>"简介",
            "level"=>"职别",
           ];
        $this->assign('data',$data);
        $this->assign('title',$title);

        return $this->fetch('list');

    }


    /**
     * 显示创建表单页.
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
        if($request->isAjax(true)){
            $data=$request->param();
            $m=new PartisanModel();
            $res=$m->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增成功!',
                    'code' => 1
                ];
//                $user=session('data');
//                $log=[
//                    'type'=>'轮播图模块',
//                    'content'=>'新增banner图!',
//                    'editor'=>$user['username'],
//                ];
//                $logmodel=new SystemLog();
//                $logmodel->allowField(true)->save($log);

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
        //

        $data=PartisanModel::get($request->param('id'));
        $this->assign('data',$data);
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
            $m=new PartisanModel();
            $res= $m->allowField(true)->save($data,['id'=>$data['id']]);
            if ($res) {
                $state = [
                    'msg' => '修改成功!',
                    'code' => 1
                ];
            } else {
                $state = [
                    'msg' => '修改失败!',
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

        $res=PartisanModel::destroy($id['id'],true);
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
            $info=$file->rule('date')->move('static/partisan');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='partisan/'.$path;

            return $filepath;
        }
    }


    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new PartisanModel();
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
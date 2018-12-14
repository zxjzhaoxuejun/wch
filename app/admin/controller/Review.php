<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-08-07
 * Time: 20:20
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Review as ReviewModel;
use think\Request;

class Review extends Base{
    /**
     * 评审专家
     * @return mixed
     */
    public function index(){
        $data=ReviewModel::all();
        $this->assign('data',$data);
        return $this->fetch('list');
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
            $nav=new ReviewModel();
            $res=$nav->allowField(true)->save($data);

            if($res){
                $state=[
                    'msg'=>'新增成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'新增失败!',
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
        $info=ReviewModel::get($request->param('id'));
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
            $nav=new ReviewModel();
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
        $res=ReviewModel::destroy($id['id'],true);
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
            $info=$file->rule('date')->move('static/review');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='review/'.$path;

            return $filepath;
        }
    }
}
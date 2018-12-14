<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-04-29
 * Time: 16:47
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Schools as SchoolModel;
use think\Request;

class Schools extends Base{
    /**
     * 显示列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data= SchoolModel::all();
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
        $cate=["培训动态","品牌人物","品牌故事","品牌发布"];
        $this->assign('cate',$cate);
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
            $art = new SchoolModel();
            $res = $art->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增文章成功!',
                    'code' => 1
                ];
            } else {
                $state = [
                    'msg' => '新增文章失败!',
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

        $info=SchoolModel::get($request->param('id'));
        $this->assign('info',$info);
        $cate=["培训动态","品牌人物","品牌故事","品牌发布"];
        $this->assign('cate',$cate);
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
            $article=new SchoolModel();
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
        $res=SchoolModel::destroy($id['id'],true);

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
            $info=$file->rule('date')->move('static/schools');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='schools/'.$path;

            return $filepath;
        }
    }

//    修改文章状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new SchoolModel();
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
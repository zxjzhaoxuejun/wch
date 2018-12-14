<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use think\Request;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Category as CategoryModel;

class Article extends Base
{
    /**
     * 显示业界动态资讯列表
     *cate_id=2
     * @return \think\Response
     */
    public function industry()
    {
        //
        $data= ArticleModel::all(function ($query){
            $query->where('cate_id=2');
        });
        $this->assign('data',$data);

        return $this->fetch('article_list');
    }


    /**
     * 显示协会活动资讯列表
     *cate_id=3
     * @return \think\Response
     */
    public function activity()
    {
        //
        $data= ArticleModel::all(function ($query){
            $query->where('cate_id=3');
        });
        $this->assign('data',$data);

        return $this->fetch('article_list');
    }

    /**
     * 显示协会新闻资讯列表
     *cate_id=4
     * @return \think\Response
     */
    public function x_news()
    {
        //
        $data= ArticleModel::all(function ($query){
            $query->where('cate_id=4');
        });
        $this->assign('data',$data);

        return $this->fetch('article_list');
    }

    /**
     * 显示政策信息资讯列表
     *cate_id=5
     * @return \think\Response
     */
    public function z_info()
    {
        //
        $data= ArticleModel::all(function ($query){
            $query->where('cate_id=5');
        });
        $this->assign('data',$data);

        return $this->fetch('article_list');
    }
    /**
     * 显示专题活动资讯列表
     *cate_id=6
     * @return \think\Response
     */
    public function h_info()
    {
        //
        $data= ArticleModel::all(function ($query){
            $query->where('cate_id=6');
        });
        $this->assign('data',$data);

        return $this->fetch('article_list');
    }

    /**
     * 显示创建资讯表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $cate=(new CategoryModel())->tree();//分层展示数据
        $this->assign('cate',$cate);
        return $this->fetch('article_add');
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
            $art = new ArticleModel();
            $res = $art->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增文章成功!',
                    'code' => 1
                ];

                $user_1=session('data');
                $log=[
                    'type'=>'资讯管理模块',
                    'content'=>'成功添加一篇资讯文章!',
                    'editor'=>$user_1['username'],
                ];
                $logmodel=new SystemLog();
                $logmodel->allowField(true)->save($log);

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

        $info=ArticleModel::get($request->param('id'));
        $this->assign('info',$info);
        $cate=(new CategoryModel())->tree();//分层展示数据
        $this->assign('cate',$cate);
        return $this->fetch('article_edit');
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
            $article=new ArticleModel();
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

        $res=ArticleModel::destroy($id['id'],true);

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
            $info=$file->rule('date')->move('static/uploads');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='uploads/'.$path;

            return $filepath;
        }
    }

//    修改文章状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $art=new ArticleModel();
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

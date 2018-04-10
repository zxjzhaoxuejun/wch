<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\Category;
use app\admin\model\Products;
use think\Request;

class Productlist extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=Products::all(function ($query){
            $query->order('order','asc');
        });
        $this->assign('data',$data);
        return $this->fetch('product_list');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $cate=Category::all(function($query){
            $query->where('cate_pid','2');
        });
        $this->assign('cate',$cate);
        return $this->fetch('product_add');
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
           $pic='';
           if(isset($data['pic'])){
               if(is_array($data['pic'])){
                   foreach ($data['pic'] as $v){
                       $pic.= $v.'@||';
                   }
                   $data['pic']=$pic;
               }
           }
            $p=new Products();
            $res=$p->allowField(true)->save($data);
           if($res){
               $state=[
                   'msg'=>'添加成功!',
                   'code'=>1
               ];
           }else{
               $state=[
                   'msg'=>'添加失败!',
                   'code'=>0
               ];
           }
           return $state;

       }else{

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
        $id=$request->param('id');
        $data=Products::get($id);
        $this->assign('data',$data);
        $cate=Category::all(function($query){
            $query->where('cate_pid','2');
        });
        $this->assign('cate',$cate);
        return $this->fetch('product_edit');
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
        if($request->isAjax(true)){
            $data=$request->param();
            $pic='';
            if(isset($data['pic'])){
                if(is_array($data['pic'])){
                    foreach ($data['pic'] as $v){
                        $pic.= $v.'@||';
                    }
                    $data['pic']=$pic;
                }
            }
            $p=new Products();
            $res=$p->allowField(true)->save($data,['id'=>$data['id']]);
            if($res){
                $state=[
                    'msg'=>'更新成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'更新失败!',
                    'code'=>0
                ];
            }
            return $state;
        }else{

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
        $res=Products::destroy($id['id'],true);
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

    public function upload_img(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/products');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='products/'.$path;
            return $filepath;
        }

    }


    //    修改产品状态
    public function changeStatus(Request $request)
    {
        if ($request->isPost(true)){
            $data=$request->param();
            $p=new Products();
            $res=$p->allowField(true)->save($data,['id'=>$data['id']]);

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
